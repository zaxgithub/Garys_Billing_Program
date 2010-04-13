<?php

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\emp.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\form.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\bslip.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");

$db = new bslip();
$form = new form();
$emp = new emp();



if(isset($_GET['id'])){$_SESSION['referrer']=$_SERVER["HTTP_REFERER"];$_POST['id']=$_GET['id'];}
if(isset($_GET['action'])){$_POST['action']=$_GET['action'];}
if($_GET['action']=="delete" && isset($_GET['id'])){	$result = $db->delete($_GET['id']);}
if($_GET['action']=="saveall"){	$result = $db->saveSlipsByEmp($_SESSION['emp_id'],CURRENT_MYSQL_TIMESTAMP,STATUS_ACTIVE);}


$db_fields = array(
									'bslip_date_worked' => 'Date of Work',
									'emp_id' => 'Employee',
									'client_id' => 'Client',
									'bslip_amount' => 'Amount',
									'bslip_remarks' => 'Remarks',
									'bslip_status' => 'Status'
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

if(!isset($_POST['bslip_date_worked'])){$_POST['bslip_date_worked']=$_COOKIE['bslip_date_worked'];}
if(!isset($_POST['emp_id'])){$_POST['emp_id']=$_COOKIE['bslip_emp_id'];}
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
	
	function confirmSave(){
		if(confirm('Are you SURE that you want to save ALL of YOUR pending slips???')){
				alert('ahha... by golly I think I\'ve got it! HaHaHa!');
				window.location='<?php echo script_name(); ?>?action=saveall';
			//if(confirm('Are you REALLY REALLY SURE? This cannot be reversed.')){
				//window.location='<?php echo script_name(); ?>?action=saveall';
			//}
		}else{
			return false;
		}
	}
	
	
</script>
<div id="intQuad">
	<div class="button"><a href="bslip_modify.php?action=add">CREATE NEW BILLING SLIP</a></div>
	<h2>BILLING SLIP</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner">
				<div style="min-height:300px;vertical-align:middle;text-align:left;">


<div align="center" style="color:green;"><?php echo $message; ?></div>
<br />




<!-- ********************************************************************************************* -->
<!-- ************************* [START] PENDING SLIPS WINDOW ************************************** -->
<?php echo strtoupper($_SESSION['emp_nickname']); ?>'s PENDING SLIPS: <a href="javascript:void(0);" id="toggle_switch" onClick="toggleHeight('pending_view','100px','auto')" style="margin-left:600px;text-decoration:none;">[ + ] EXPAND VIEW</a>
<div id="pending_view" style="margin:10px 0px 0px 0px;border:solid 1px #ccc;background:#f9f9f9;width:auto;height:100px;overflow:auto;clear:both;text-align:center;">
<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:5px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head">EMPLOYEE</td>
		<td class="ordertable_head">CLIENT</td>
		<td class="ordertable_head">AMOUNT</td>
		<td class="ordertable_head">DATE</td>
		<td class="ordertable_head">REMARKS</td>
		<td class="ordertable_head">ACTION</td>
	</tr>
<?php

	if (mysql_num_rows($p_rows) > 0) {
		while($p_row = mysql_fetch_assoc($p_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['emp_nickname']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".makeMoney($p_row['bslip_amount'])."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".format_date($p_row['bslip_date_worked'],'small')."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".shortString($p_row['bslip_remarks'])."</td>\r\n";
			echo "<td class=\"ordertable_action\"><a href=\"bslip_modify.php?id=".$p_row['bslip_id']."&action=edit\" id=\"link\">EDIT</a><a href=\"javascript:void(0);\" onClick=\"deleteRecord(".$p_row['bslip_id'].");\" id=\"link\">DELETE</a></td>\r\n";
			echo "</tr>\r\n";
		}
	}else{
		echo "<tr><td align=\"center\" colspan=\"5\">\r\n";
		echo "There are no pending slips for you ".$_SESSION['emp_nickname']."!<br />\r\n";
		echo "Remember... you can only clear the slips YOU added.\r\n";
		echo "</td></tr>\r\n";
	}

?>
</table>

</div>

<!-- ************************* [END] PENDING SLIPS WINDOW ************************************** -->
<!-- ********************************************************************************************* -->

<br class="clearit" />
<input type="button" onclick="javascript:confirmSave();" value="Save Pending Slips" />

<div style="height:1px;width:90%;margin:10px;border-top:dotted 1px #ccc;clear:both;"></div>


					<form method="post" action="<?php echo script_name(); ?>" name="form1" id="form1" enctype="multipart/form-data">
					<table class="formtable" cellpadding="0" cellspacing="0">
<?php
		foreach($db_fields As $k => $v){
			if($k=="emp_id"){
				//$inputHTML = $form->getEmpSelect($_POST['emp_id']);
				$inputHTML = "<div id=\"emp_id\"></div>";
			}elseif($k=="client_id"){
//				$inputHTML = $form->getClientSelect($_POST['client_id']);
				$inputHTML = "<div id=\"client_id\"></div>";
			}elseif($k=="svc_id"){
				$inputHTML = $form->getSvcSelect($_POST['svc_id']);
			}elseif($k=="bslip_remarks"){
				$inputHTML = "<textarea class=\"alt\" id=\"".$k."\" name=\"".$k."\">".$_POST[$k]."</textarea>";
			}elseif($k=="bslip_status"){
				if($_POST['action']!="add" && $_POST['action']!="add_submit"){
					$inputHTML = $form->getStatusSelect("bslip_status",$_POST['bslip_status']);
				}else{
					$inputHTML = false;
				}
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
							<?php echo "Last Modified on ".format_date($_POST['bslip_date_modified'],'datetime')." by ".$emp->getNameById($_POST['bslip_emp_modified']); ?>
							<input type="hidden" name="bslip_date_modified" value="<?php echo $_POST['bslip_date_modified']; ?>" />
							<input type="hidden" name="bslip_emp_modified" value="<?php echo $_POST['bslip_emp_modified']; ?>" />
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



<!-- ########################## -->
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
<!-- ########################## --> 


<!-- ########################## -->
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
<!-- ########################## --> 


<!-- // JQUERY DATEPICKER -->			
<script type="text/javascript">
$(document).ready(function(){
$("#bslip_date_worked").datepicker({
		defaultDate: new Date('<?php echo $_POST['bslip_date_worked']; ?>'),
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