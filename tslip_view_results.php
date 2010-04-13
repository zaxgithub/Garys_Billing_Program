<?php 

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\functions\\utility.functions.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\tslip.class.php");


$db = new tslip();
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
	
	function pendingTotalSelected(){
		var selected_count = 0;
		$("input[name$='tslip_pending_selected[]']").each(function()
		{
			if(this.checked){
				selected_count++;
			}
		});
		return selected_count;
	}


	function confirmSavePending(){
		var num_selected = pendingTotalSelected();
		if( num_selected<1 ){
			alert("You must first select the records you wish to save");
			return false;
		}
		if(confirm('Are you SURE that you want to ** SAVE **  ( '+num_selected+' ) pending slips???')){
			$("#form_tslip_pending_action").val("savepending");
				document.form_tslip_pending.submit();
		}else{
			return false;
		}
	}
	function confirmDeletePending(){
		var num_selected = pendingTotalSelected();
		if(confirm('Are you SURE that you want to ** DELETE **  ( '+num_selected+' ) pending slips???')){
			$("#form_tslip_pending_action").val("deletepending");
				document.form_tslip_pending.submit();

		}else{
			return false;
		}
	}

$(document).ready(function(){

	$("#checkboxall").click(function()
	{

		var checked_status = this.checked;
		$("input[name$='tslip_selected[]']").each(function()
		{
			this.checked = checked_status;
		});
	});
});


	$("#checkboxall_pending").click(function()
	{
		var checked_status = this.checked;
		$("input[name$='tslip_pending_selected[]']").each(function()
		{
			this.checked = checked_status;
		});
	});


	$("#delete_slips_button").click(function()
	{
			var cb = document.tslip_list_form.tslip_selected;
			var cb_array = "";
		var checked_status = true;
		$("input[name$='tslip_selected[]']").each(function()
		{
			if(this.checked == checked_status){
				cb_array+=this.value+',';
			}
		});
		
			alert(cb_array);
			if(cb_array!=""){
				$.post("process/tslip.php", 
					{	ids: cb_array,
						action: "deleteSelected"
					},
				  function(data){
				    alert(data);
			 		 	window.location.reload();
				  },
				  "html"
			  );
			}else{
				alert('Nothing selected. Please select items to clear.');
			}

		});


	-->
</script>

<?php //echo $pg_prev.$pg_next.$pg_info; ?>
<form name="tslip_list_form" id="tslip_list_form">
<div align="center">
<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:20px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head"><?php echo makeCheckBox('checkboxall','',''); ?></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'emp_id','<?php echo switchDir($_POST['order_dir']); ?>');">EMPLOYEE</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_id','<?php echo switchDir($_POST['order_dir']); ?>');">CLIENT</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'svc_id','<?php echo switchDir($_POST['order_dir']); ?>');">SERVICE</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'tslip_period_ending','<?php echo switchDir($_POST['order_dir']); ?>');">ENDING</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'tslip_remarks','<?php echo switchDir($_POST['order_dir']); ?>');">REMARKS</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'tslip_hours','<?php echo switchDir($_POST['order_dir']); ?>');">HOURS</a></td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'tslip_amount','<?php echo switchDir($_POST['order_dir']); ?>');">AMOUNT</a></td>
		<td class="ordertable_head">ACTION</td>
	</tr>
<?php

	if (mysql_num_rows($db_rows) > 0) {
		while($db_row = mysql_fetch_assoc($db_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\">".makeCheckBox("tslip_selected[]",$db_row["tslip_id"],"")."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['emp_nickname']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['svc_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".format_date($db_row['tslip_period_ending'],'small')."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".shortString($db_row['tslip_remarks'])."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['tslip_hours']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$db_row['tslip_amount']."</td>\r\n";
			echo "<td class=\"ordertable_action\"><a href=\"tslip_modify.php?id=".$db_row['tslip_id']."&action=edit\" id=\"link\">EDIT</a><a href=\"javascript:void(0);\" onClick=\"deleteRecord(".$db_row['tslip_id'].");\" id=\"link\">DELETE</a></td>\r\n";
			echo "</tr>\r\n";
		}
	}else{
		echo "<tr><td align=\"center\" colspan=\"5\">\r\n";
		echo "There are no results!\r\n";
		echo "</td></tr>\r\n";
	}

?>
</table>
<p>


<input type="button" onclick="" id="delete_slips_button" value=" DELETE SELECTED " style="cursor:pointer;background-color:red;color:#fff;font-weight:bold;padding:3px;border:solid 2px pink;" />



<br /></p>
</div>
</form>
<?php echo $pg_prev.$pg_next.$pg_info; ?>

<?php
echo "<hr>";
var_dump($_POST);
?>