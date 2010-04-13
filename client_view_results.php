<?php 

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\client.class.php");


$db = new client();
if($_GET['action']=="delete" && isset($_GET['id'])){	$result = $db->delete($_GET['id']);}



// ############ PAGINATION ############
$_POST['row_total'] = $db->getAllTotal($_POST['search_term']); //TOTAL NUMBER OF ROWS
$_POST['page_last'] = ceil($_POST['row_total']/$_POST['page_size']); //CALCULATE LAST PAGE NUMBER
if ($_POST['page_num'] > $_POST['page_last']) {	$_POST['page_num'] = $lastpage;} //DONT DO BEYOND LAST PAGE
if ($_POST['page_num'] < 1) {	$_POST['page_num'] = 1;}//DONT GO LESS THAN PAGE 1
$_POST['row_limit'] = ($_POST['page_num'] - 1) * $_POST['page_size'];
$pg_info = "Page ".$_POST['page_num']." of ".$_POST['page_last']." (".$_POST['row_total']." records)<br class=\"clearit\" />";
$pg_next = "<a href=\"javascript:void(0);\" onClick=\"orderTable(".($_POST['page_num']+1).",".$_POST['page_size'].",'".$_POST['order_by']."','".$_POST['order_dir']."');\" style=\"float:right;margin:2px 50px;\">NEXT >></a>";
$pg_prev = "<a href=\"javascript:void(0);\" onClick=\"orderTable(".($_POST['page_num']-1).",".$_POST['page_size'].",'".$_POST['order_by']."','".$_POST['order_dir']."');\" style=\"float:left;margin:2px 50px;\"><< PREVIOUS</a>";
if($_POST['page_num']>=$_POST['page_last']){$pg_next="<div style=\"float:right;margin:2px 50px;width:60px;\"></div>";}
if($_POST['page_num']<=1){$pg_prev="<div style=\"float:left;margin:2px 50px;width:60px;\"></div>";}
// ############ /PAGINATION ############

// ############ SIMPLE SEARCH ########### //
$db_rows = $db->getAllSimpleSearch($_POST['row_limit'], $_POST['page_size'], $_POST['order_by'], $_POST['order_dir'], $_POST['search_term']);

?>
<script type="text/javascript">

function printwin()
{
var testwindow = window.open("client_print.php", "printwindow", "location=0,status=0,scrollbars=1,width=700,height=500");
testwindow.moveTo(0,0);
testwindow.focus();
}

</script>
<input type="button" name="printpage" id="printpage" value=" PRINT VIEW " onclick="printwin();" />
<?php echo $pg_prev.$pg_next.$pg_info; ?>
<div align="center">
<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:20px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_id','<?php echo switchDir($_POST['order_dir']); ?>');">CLIENT #</td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_name','<?php echo switchDir($_POST['order_dir']); ?>');">NAME</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_contact','<?php echo switchDir($_POST['order_dir']); ?>');">CONTACT</td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_phone','<?php echo switchDir($_POST['order_dir']); ?>');">PHONE</td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_status','<?php echo switchDir($_POST['order_dir']); ?>');">STATUS</td>
		<td class="ordertable_head">ACTION</td>
	</tr>
<?php

	if (mysql_num_rows($db_rows) > 0) {
		while($db_row = mysql_fetch_assoc($db_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_name']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_contact']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_phone']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_status_name']."</td>\r\n";
			echo "<td class=\"ordertable_action\">";
				echo "<a href=\"client_modify.php?id=".$db_row['client_id']."&action=edit\" id=\"link\">EDIT</a>";
				echo "<a href=\"javascript:void(0);\" onClick=\"deleteRecord(".$db_row['client_id'].");\" id=\"link\">DELETE</a>";
				echo "<a href=\"billing_view.php?id=".$db_row['client_id']."&action=view\" id=\"link\">BILLING</a>";
			echo "</td>\r\n";
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
