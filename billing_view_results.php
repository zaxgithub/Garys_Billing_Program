<?php

error_reporting (E_ALL ^ E_NOTICE);
setlocale(LC_MONETARY, 'en_US');


require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\functions\\utility.functions.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\billing.class.php");


$db = new billing();
if($_GET['action']=="delete" && isset($_GET['id']) && isset($_GET['table'])){	$result = $db->delete($_GET['id'],$_GET['table']);}

if($_REQUEST['action']=="clear" && !empty($_REQUEST['ids'])){
	$ids = trim($_REQUEST['ids'],",");
	$a = array("tslip_status"=>STATUS_CLEARED);
	$db->update_all_in($a,$ids);
}

// ############ PAGINATION ############
$_POST['row_total'] = "";//$db->getAllTotal($_POST['search_term']); //TOTAL NUMBER OF ROWS
$_POST['page_last'] = ceil($_POST['row_total']/$_POST['page_size']); //CALCULATE LAST PAGE NUMBER
if ($_POST['page_num'] > $_POST['page_last']) {	$_POST['page_num'] = $lastpage;} //DONT DO BEYOND LAST PAGE
if ($_POST['page_num'] < 1) {	$_POST['page_num'] = 1;}//DONT GO LESS THAN PAGE 1
$_POST['row_limit'] = ($_POST['page_num'] - 1) * $_POST['page_size'];
$pg_next = "<a href=\"javascript:void(0);\" onClick=\"orderTable(".($_POST['page_num']+1).",".$_POST['page_size'].",'".$_POST['order_by']."','".$_POST['order_dir']."');\" style=\"float:right;margin:2px 50px;\">NEXT >></a>";
$pg_prev = "<a href=\"javascript:void(0);\" onClick=\"orderTable(".($_POST['page_num']-1).",".$_POST['page_size'].",'".$_POST['order_by']."','".$_POST['order_dir']."');\" style=\"float:left;margin:2px 50px;\"><< PREVIOUS</a>";
if($_POST['page_num']>=$_POST['page_last']){$pg_next="<div style=\"float:right;margin:2px 50px;width:60px;\"></div>";}
if($_POST['page_num']<=1){$pg_prev="<div style=\"float:left;margin:2px 50px;width:60px;\"></div>";}
// ############ /PAGINATION ############

$search_date = "";
$search_client = "";
$search_status = "";
// ############ SIMPLE SEARCH ########### //


$db_rows = $db->getAllBySearch($_POST);

$_POST['row_total'] = mysql_num_rows($db_rows);

$pg_info = "Page ".$_POST['page_num']." of ".($_POST['page_last']<1?"1":$_POST['page_last'])." (".$_POST['row_total']." records)";

?>






<script type="text/javascript" language="Javascript">
	<!--//
	function deleteBillingRecord(id,tbl){
		if(confirm('Are you sure you want to permanently delete this slip #'+id+'?')){
			//window.location.href = "<?php echo script_name(); ?>?action=delete&id=" + id + "&table=" + tbl;
			document.order_form.search_term.value = "null";
			document.order_form.action = "billing_view.php?action=delete&id=" + id + "&table=" + tbl;
			document.order_form.submit();
		}
	}
	$("#checkboxall").click(function()
	{
		var checked_status = this.checked;
		$("input[name=billing_selected]").each(function()
		{
			this.checked = checked_status;
		});
	});


	-->
</script>


<?php echo $pg_prev.$pg_next.$pg_info; ?>
<form name="billing_form" id="billing_form">
<div align="center">
<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:20px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head" style="text-align:left;font-size:7px;" valign="middle"><?php echo makeCheckBox('checkboxall','',''); ?></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_id','<?php echo switchDir($_POST['order_dir']); ?>');" title="Record Number">ID</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_client','<?php echo switchDir($_POST['order_dir']); ?>');" title="Client Name">CLIENT</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_employee','<?php echo switchDir($_POST['order_dir']); ?>');" title="Employee Name">EMP</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_date','<?php echo switchDir($_POST['order_dir']); ?>');" title="Date of billing">DATE</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_hours','<?php echo switchDir($_POST['order_dir']); ?>');" title="Hours worked">HRS</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_amount','<?php echo switchDir($_POST['order_dir']); ?>');" title="Amount of charge">AMT</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_remarks','<?php echo switchDir($_POST['order_dir']); ?>');" title="Remarks">RMKS</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'billing_status','<?php echo switchDir($_POST['order_dir']); ?>');" title="Status of this record. (A)ctive or (C)leared">S</a></td>
		<td class="ordertable_head">&nbsp;</td>
	</tr>
<?php
    $a = 0;
    $group_bulling_total = false;
    $ids_to_clear = "";
	if (mysql_num_rows($db_rows) > 0) {
		while($db_row = mysql_fetch_assoc($db_rows)){
			if($prev_billing_client==$db_row['billing_client']){
				$class = "";
				$pre_html = "";
				$post_html = "";
				$group_bulling_total += (float)$db_row['billing_amount'];
			}else{
				$post_html = ($group_bulling_total!==false) ? "<tr class=\"ordertable_row group_total\"><td colspan=\"6\">&nbsp;</td><td>".makeMoney($group_bulling_total)."</td><td colspan=\"3\">&nbsp;</td></tr>\r\n" : "";
				$group_bulling_total = (float)$db_row['billing_amount'];
				$pre_html = "<tr class=\"ordertable_row group_title\"><td colspan=\"10\">".$db_row['billing_client']." [ #".$db_row['client_id']." ] <a id='small' href='client_modify.php?id=".$db_row['client_id']."&action=edit'>edit client info</a>"."</td></tr>\r\n";
				$class = "";//" group_start";
				$prev_billing_client=$db_row['billing_client'];
			}
			
			$hidden_amount_name = "billing_amount_".$db_row["billing_id"];
			$hidden_amount = "<input type=\"hidden\" name=\"".$hidden_amount_name."\" id=\"".$hidden_amount_name."\" value=\"" . $db_row['billing_amount'] . "\" />";
			
			
		    ($db_row['billing_type']=="bill") ? $table = "bslip" : $table = "tslip";
            $a += $db_row['billing_amount'];
            $ids_to_clear .= $table.$db_row['billing_id'].",";
      echo $post_html;
      echo $pre_html;
			echo "<tr class=\"ordertable_row".$class."\">\r\n";
			echo "<td class=\"ordertable_orderno\">".makeCheckBox("billing_selected",$db_row["billing_id"],"")."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['billing_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['billing_client']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['billing_employee']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".format_date($db_row['billing_date'],"small")."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['billing_hours']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".makeMoney($db_row['billing_amount']).$hidden_amount."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".shortString($db_row['billing_remarks'])."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".substr($db_row['billing_status'],0,1)."</td>\r\n";
//			echo "<td class=\"ordertable_action\"><a href=\"".$table."_modify.php?id=".$db_row['billing_id']."&action=edit\" id=\"link\">EDIT</a><a href=\"javascript:void(0);\" onClick=\"deleteBillingRecord(".$db_row['billing_id'].",'".$table."');\" id=\"link\">DELETE</a></td>\r\n";
			echo "<td class=\"ordertable_orderno\"><a href=\"".$table."_modify.php?id=".$db_row['billing_id']."&action=edit\" title=\"EDIT this record.\"><img src=\"images/edit.gif\" border=\"0\" alt=\"edit\" /></a></td>\r\n";
			echo "</tr>\r\n";
		}
		$post_html = ($group_bulling_total!==false) ? "<tr class=\"ordertable_row group_total\"><td colspan=\"6\">&nbsp;</td><td>".makeMoney($group_bulling_total)."</td><td colspan=\"3\">&nbsp;</td></tr>\r\n" : "";
		echo $post_html;
	}else{
		echo "<tr><td align=\"center\" colspan=\"5\">\r\n";
		echo "There are no results!\r\n";
		echo "</td></tr>\r\n";
	}

?>
</table>
</div>
<?php echo "<p>total amount: ".makeMoney($a)."</p><br />"; ?>

<p>

<!-- <a href="javascript:void(0);" onclick="javascript:clearBilling();">CLEAR BILLING</a> -->

<input type="button" onclick="javascript:clearBilling();" value=" CLEAR SELECTED " style="position:absolute;top:137px;left:100px;cursor:pointer;background-color:green;color:#fff;font-weight:bold;padding:3px;border:solid 2px black;" /> &nbsp; &nbsp; 
<input type="button" onclick="javascript:deleteBilling();" value=" DELETE SELECTED " style="position:absolute;top:137px;left:280px;cursor:pointer;background-color:red;color:#fff;font-weight:bold;padding:3px;border:solid 2px pink;" />



<br /></p>
</form>
<?php echo $pg_prev.$pg_next.$pg_info; ?>


<?php 
/* //DATE TESTS AND EXAMPLES
echo viewGetPost();
echo "<hr />";
echo "Period From 1-05: ".getDatePeriod('2009-01-05');
echo "<hr />";
echo "Period From 2-10: ".getDatePeriod('2009-02-10');
echo "<hr />";
echo "Period From 2-15: ".getDatePeriod('2009-02-15');
echo "<hr />";
echo "Period From 2-16: ".getDatePeriod('2009-02-16');
echo "<hr />";
echo "null date: ".getDatePeriod();
echo "<hr />";
echo "getDateArray: ";
print_r(getDateArray('2009-01-05'));
echo "<hr />";
echo "time: ";
echo date('Y-m-d',time())."\n";
*/
?>
