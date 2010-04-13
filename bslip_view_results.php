<?php 

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\bslip.class.php");


$db = new bslip();
if($_GET['action']=="delete" && isset($_GET['id'])){	$result = $db->delete($_GET['id']);}


// ############ PAGINATION ############
$_POST['row_total'] = $db->getAllTotal($_POST['search_term']); //TOTAL NUMBER OF ROWS
$_POST['page_last'] = ceil($_POST['row_total']/$_POST['page_size']); //CALCULATE LAST PAGE NUMBER
if ($_POST['page_num'] > $_POST['page_last']) {	$_POST['page_num'] = $lastpage;} //DONT DO BEYOND LAST PAGE
if ($_POST['page_num'] < 1) {	$_POST['page_num'] = 1;}//DONT GO LESS THAN PAGE 1
$_POST['row_limit'] = ($_POST['page_num'] - 1) * $_POST['page_size'];
$pg_info = "Page ".$_POST['page_num']." of ".$_POST['page_last']." (".$_POST['row_total']." records)";
$pg_next = "<a href=\"javascript:void(0);\" onClick=\"orderTable(".($_POST['page_num']+1).",".$_POST['page_size'].",'".$_POST['order_by']."','".$_POST['order_dir']."');\" style=\"float:right;margin:2px 50px;\">NEXT >></a>";
$pg_prev = "<a href=\"javascript:void(0);\" onClick=\"orderTable(".($_POST['page_num']-1).",".$_POST['page_size'].",'".$_POST['order_by']."','".$_POST['order_dir']."');\" style=\"float:left;margin:2px 50px;\"><< PREVIOUS</a>";
if($_POST['page_num']>=$_POST['page_last']){$pg_next="<div style=\"float:right;margin:2px 50px;width:60px;\"></div>";}
if($_POST['page_num']<=1){$pg_prev="<div style=\"float:left;margin:2px 50px;width:60px;\"></div>";}
// ############ /PAGINATION ############

// ############ SIMPLE SEARCH ########### //
$db_rows = $db->getAllSimpleSearch($_POST['row_limit'], $_POST['page_size'], $_POST['order_by'], $_POST['order_dir'], $_POST['search_term']);

?>

<?php echo $pg_prev.$pg_next.$pg_info; ?>
<div align="center">
<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:20px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'emp_id','<?php echo switchDir($_POST['order_dir']); ?>');">EMPLOYEE</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_id','<?php echo switchDir($_POST['order_dir']); ?>');">CLIENT</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'bslip_amount','<?php echo switchDir($_POST['order_dir']); ?>');">AMOUNT</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'bslip_date_entered','<?php echo switchDir($_POST['order_dir']); ?>');">CREATED</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'bslip_remarks','<?php echo switchDir($_POST['order_dir']); ?>');">REMARKS</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'bslip_status','<?php echo switchDir($_POST['order_dir']); ?>');">STATUS</a></td>
		<td class="ordertable_head">ACTION</td>
	</tr>
<?php

	if (mysql_num_rows($db_rows) > 0) {
		while($db_row = mysql_fetch_assoc($db_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['emp_nickname']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['bslip_amount']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".format_date($db_row['bslip_date_entered'],'small')."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".shortString($db_row['bslip_remarks'])."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['bslip_status_name']."</td>\r\n";
			echo "<td class=\"ordertable_action\"><a href=\"bslip_modify.php?id=".$db_row['bslip_id']."&action=edit\" id=\"link\">EDIT</a><a href=\"javascript:void(0);\" onClick=\"deleteRecord(".$db_row['bslip_id'].");\" id=\"link\">DELETE</a></td>\r\n";
			echo "</tr>\r\n";
		}
	}else{
		echo "<tr><td align=\"center\" colspan=\"5\">\r\n";
		echo "There are no results!\r\n";
		echo "</td></tr>\r\n";
	}

?>
</table>
</div>
<?php echo $pg_prev.$pg_next.$pg_info; ?>
