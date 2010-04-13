<?php

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\emp.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\form.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\tslip.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");

$emp = new emp();
$db = new tslip();
$form = new form();



if(isset($_GET['id'])){$_SESSION['referrer']=$_SERVER["HTTP_REFERER"];$_POST['id']=$_GET['id'];}
if(isset($_GET['action'])){$_POST['action']=$_GET['action'];}
if($_GET['action']=="delete" && isset($_GET['id'])){	$result = $db->delete($_GET['id']);}
if($_GET['action']=="saveall"){	$result = $db->saveSlipsByEmp($_SESSION['emp_id'],CURRENT_MYSQL_TIMESTAMP,STATUS_ACTIVE);}

if($_REQUEST['action']=="savepending" && is_array($_POST['tslip_pending_selected'])){	
	$result = $db->save_slips_in(implode(",",$_POST['tslip_pending_selected']));
}
if($_REQUEST['action']=="deletepending" && is_array($_POST['tslip_pending_selected'])){	
	$result = $db->delete_slips_in(implode(",",$_POST['tslip_pending_selected']));
}



$db_fields = array(
									'emp_id' => 'Employee',
									'client_id' => 'Client',
									'svc_id' => 'Service',
									//'tslip_cleared' => 'Cleared',
									'tslip_period_ending' => 'Period Ending',
									'tslip_hours' => 'Hours',
									'tslip_chargable' => 'Chargable',
									//'tslip_expense' => 'Expense',
									'tslip_amount' => 'Amount',
									'tslip_remarks' => 'Remarks',
									'tslip_status' => 'Status'
										);




// EDIT RECORD
	if($_POST['action']=="edit"){
		$_POST['new_action'] = "edit_submit";
		$db_rows = $db->getSingle($_POST['id']);
		if (mysql_num_rows($db_rows) > 0) {
			$db_row = mysql_fetch_assoc($db_rows);
			foreach($db_row As $k => $v){
				$_POST[$k] = $db_row[$k];
			}
		}

// ADD RECORD
	}elseif($_POST['action']=="add"){
		$_POST['new_action'] = "add_submit";
		$post_array = killPostArray($db_fields);
		
		
// EDIT FORM POSTED
	}elseif($_POST['action']=="edit_submit"){
		$_POST['new_action'] = "add";
		$post_array = makePostArray($db_fields);// This is so dumb... I am 99% sure i can remove this. But I AM 1% not sure. $_POST is an array... duuuuh!
		$db_rows = $db->update($post_array,$_POST['id']);
		header("location: ".$_SESSION['referrer']);

// ADD FORM POSTED
	}elseif($_POST['action']=="add_submit"){
		$db->setCookies();
		$_POST['new_action'] = "add";
		$post_array = makePostArray($db_fields); // This is so dumb... I am 99% sure i can remove this. But I AM 1% not sure. $_POST is an array... duuuuh!
		$db_rows = $db->insert($post_array);
		header("location: ".script_name()."?action=add");
	}
// GRAB PENDING SLIPS FOR THIS EMPLOYEE WHO IS LOGGED IN
$p_rows = $db->getAllPending($_SESSION['emp_id']);

if(!isset($_POST['tslip_period_ending'])){$_POST['tslip_period_ending']=$_COOKIE['tslip_period_ending'];}
if(!isset($_POST['emp_id'])){$_POST['emp_id']=$_COOKIE['tslip_emp_id'];}
?>
<script type="text/javascript">
	function toggleHeight(EL,MIN_VAL,MAX_VAL){
		var toggle_el_id = "toggle_switch";
		var toggle_msg_expand = "[ + ] EXPAND VIEW";
		var toggle_msg_colapse = "[ - ] COLAPSE VIEW";
		
		if(document.getElementById(EL).style.height==MIN_VAL){
			document.getElementById(EL).style.height=MAX_VAL;
			document.getElementById(toggle_el_id).innerHTML=toggle_msg_colapse;
		}else{
			document.getElementById(EL).style.height=MIN_VAL;
			document.getElementById(toggle_el_id).innerHTML=toggle_msg_expand;
		}
	}
	
	function pendingTotalSelected(){
		var selected_count = 0;
		$("input[name='tslip_pending_selected[]']").each(function()
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


	$("#checkboxall_pending").click(function()
	{
		var checked_status = this.checked;
		$("input[name='tslip_pending_selected[]']").each(function()
		{
			this.checked = checked_status;
		});
	});
	
	
	
});
	
	
</script>
<div id="intQuad">
	<div class="button"><a href="tslip_modify.php?action=add">CREATE NEW TIME SLIP</a></div>
	<h2>TIME SLIP</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner">
				<div style="min-height:300px;vertical-align:middle;text-align:center;">



<div align="center" style="color:green;"><?php echo $message; ?></div>
<br class="clearit" />



				
<form action="<?php echo script_name(); ?>" method="post" name="form_tslip_pending" id="form_tslip_pending">

<div style="margin:20px 10px 10px 10px;padding:0px 10px;border:solid 1px #ccc;background:#f9f9f9;width:auto;clear:both;float:none;" align="center">
<!-- ********************************************************************************************* -->
<!-- ************************* [START] PENDING SLIPS WINDOW ************************************** -->
<?php 	if (mysql_num_rows($p_rows) > 0) {?>
<div align="center" style="color:green;"><?php echo $message; ?></div>
<br class="clearit" />
<?php echo strtoupper($_SESSION['emp_nickname']); ?>'s PENDING TIME SLIPS: <a href="javascript:void(0);" id="toggle_switch" onClick="toggleHeight('pending_view','100px','auto')" style="margin-left:600px;text-decoration:none;">[ + ] EXPAND VIEW</a>



<div id="pending_view" style="margin:10px 0px 0px 0px;border:solid 1px #ccc;background:#FEEFFF;width:auto;height:100px;overflow:auto;clear:both;float:none;" align="center">


<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:5px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head"><?php echo makeCheckBox('checkboxall_pending','',''); ?></td>
		<td class="ordertable_head">EMPLOYEE</td>
		<td class="ordertable_head">CLIENT</td>
		<td class="ordertable_head">HOURS</td>
		<td class="ordertable_head">AMOUNT</td>
		<td class="ordertable_head">PERIOD END</td>
		<td class="ordertable_head">REMARKS</td>
		<td class="ordertable_head">ACTION</td>
	</tr>
<?php
	$total_hours = 0;
	$total_amount = 0;

		while($p_row = mysql_fetch_assoc($p_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\">".makeCheckBox("tslip_pending_selected[]",$p_row["tslip_id"],"")."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['emp_nickname']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['tslip_hours']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".makeMoney($p_row['tslip_amount'])."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".format_date($p_row['tslip_period_ending'],'small')."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".shortString($p_row['tslip_remarks'])."</td>\r\n";
			echo "<td class=\"ordertable_action\"><a href=\"tslip_modify.php?id=".$p_row['tslip_id']."&action=edit\" id=\"link\">EDIT</a><a href=\"javascript:void(0);\" onClick=\"deleteRecord(".$p_row['tslip_id'].");\" id=\"link\">DELETE</a></td>\r\n";
			echo "</tr>\r\n";
			$total_hours += $p_row['tslip_hours'];
			$total_amount += $p_row['tslip_amount'];
		}

?>
</table>
</div>

Total Hours: <?php echo $total_hours; ?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
Total Amount: <?php echo makeMoney($total_amount); ?>
<br />

<br class="clearit" />
<input type="button" onclick="javascript:confirmSavePending();" value=" SAVE SELECTED " style="cursor:pointer;background-color:green;color:#fff;font-weight:bold;padding:3px;border:solid 2px black;" /> &nbsp; &nbsp; 
<input type="button" onclick="javascript:confirmDeletePending();" value=" DELETE SELECTED " style="cursor:pointer;background-color:red;color:#fff;font-weight:bold;padding:3px;border:solid 2px pink;" />
<input type="hidden" name="action" id="form_tslip_pending_action" value="" />

<div style="height:1px;width:90%;margin:10px;border-top:dotted 1px #ccc;clear:both;"></div>
<?php 	} ?>
<!-- ************************* [END] PENDING SLIPS WINDOW ************************************** -->
<!-- ********************************************************************************************* -->
</div>
</form>


					<form method="post" action="<?php echo script_name(); ?>" name="form1" id="form1" enctype="multipart/form-data">
					<table class="formtable" cellpadding="0" cellspacing="0">
<?php
		foreach($db_fields As $k => $v){
			if($k=="emp_id"){
				$inputHTML = "<div id=\"emp_id\"></div>";
			}elseif($k=="client_id"){
				$inputHTML = "<div id=\"client_id\"></div>";
			}elseif($k=="svc_id"){
				$inputHTML = "<div id=\"svc_id\"></div>";
			}elseif($k=="tslip_remarks"){
				$inputHTML = "<textarea class=\"alt\" id=\"".$k."\" name=\"".$k."\">".$_POST[$k]."</textarea>";
			}elseif($k=="tslip_status"){
				if($_POST['action']!="add" && $_POST['action']!="add_submit"){
					$inputHTML = $form->getStatusSelect($k,$_POST[$k]);
				}else{
					$inputHTML = false;
				}
			}elseif($k=="tslip_cleared" || $k=="tslip_chargable"){
				$inputHTML = $form->getYesNoSelect($k,$_POST[$k]);
			}else{
				$inputHTML = "<input class=\"alt\" id=\"".$k."\" type=\"text\" name=\"".$k."\" value=\"".$_POST[$k]."\" />";
			}
			if($inputHTML){
?>
					<tr valign="top">
						<td class="inputLabel" <?php errs($errors[$k]); ?> ><?php echo $v; ?></td>
						<td class="inputField"><?php echo $inputHTML; ?></td>
					</tr>
<?php
			}
		}
if($_POST['id']!=""){
?>
					<tr valign="top">
						<td colspan="2" class="inputLabel" style="text-align:center;font-weight:normal;color:#999;font-style:italic;font-size:14px;" <?php errs($errors[$k]); ?> >
							<?php echo "Last Modified on ".format_date($_POST['tslip_date_modified'],'datetime')." by ".$emp->getNameById($_POST['tslip_emp_modified']); ?>
							<input type="hidden" name="tslip_date_modified" value="<?php echo $_POST['tslip_date_modified']; ?>" />
							<input type="hidden" name="tslip_emp_modified" value="<?php echo $_POST['tslip_emp_modified']; ?>" />
						</td>
					</tr>
<?
}
?>
					<tr valign="top">
						<td class="inputLabel">&nbsp;</td>
						<td><br /><input class="button" type="submit" name="btnSubmit" value="SUBMIT" alt="SUBMIT" /></td>
					</tr>
					</table>
					<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />
					<input type="hidden" name="action" value="<?php echo $_POST['new_action']; ?>" />
					</form>


				</div>
		</div>
	</div>
</div>

<!-- ################################ -->
<!-- TSLIP SVC CRG CALCULATION AJAX   -->
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
	$("#svc_id_input,#emp_id_input,#tslip_hours").keyup(function() {
	$.post("tslip_lookup_price.php<?php $q = $_SERVER['QUERY_STRING'];if($q)echo "?".$q;?>", 
				{	svc_id: $("#svc_id_hidden").val(),
					emp_id: $("#emp_id_hidden").val(),
					tslip_hours: $("#tslip_hours").val()},
			  function(data){
			    $('#tslip_amount').val(data);
			  },
			  "html"
			  );
		});
});
</script>


	

<!-- ################################ -->
<!-- EMPLOYEE LOOKUP : JQUERY : FLEXBOX -->
<script type="text/javascript">
var eid = $('#emp_id').flexbox("emp_lookup.php", {
allowInput: true,
autoCompleteFirstMatch: true,
arrowQuery: "null",
paging: false,
maxVisibleRows: 8
});
eid.setValue('<?php echo $_POST['emp_id']; ?>');
</script>



<!-- ################################## -->
<!-- CLIENT LOOKUP : JQUERY : FLEXBOX -->
<script type="text/javascript">
var cid = $('#client_id').flexbox("client_lookup.php", {
allowInput: true,
autoCompleteFirstMatch: true,
arrowQuery: "null",
paging: false,
maxVisibleRows: 8
});
cid.setValue('<?php echo $_POST['client_id']; ?>');
</script>

<!-- ############################# -->
<!-- SVC LOOKUP : JQUERY : FLEXBOX -->
<script type="text/javascript">
var cid = $('#svc_id').flexbox("svc_lookup.php", {
allowInput: true,
autoCompleteFirstMatch: true,
arrowQuery: "null",
paging: false,
maxVisibleRows: 8
});
cid.setValue('<?php echo $_POST['svc_id']; ?>');
</script>



<!-- ############################################### -->
<!-- PERIOD ENDING DATE LOOKUP : JQUERY : DATEPICKER -->
<script type="text/javascript">
$(document).ready(function(){
$("#tslip_period_ending").datepicker({
		defaultDate: new Date(<?php echo $_POST['tslip_period_ending']; ?>),
    dateFormat: $.datepicker.ISO_8601, 
    showOn: "button", 
    buttonImage:  "images/calendar_popup.gif", 
    buttonImageOnly: true 
});
});
</script> 


<?php 
require_once(realpath(dirname(__FILE__))."\\includes\\footer.php");
?>