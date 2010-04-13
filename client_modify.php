<?php 
session_start(); 
ob_start();

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\form.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\client.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");


if(isset($_GET['id'])){$_POST['id']=$_GET['id'];}
if(isset($_GET['action'])){$_POST['action']=$_GET['action'];}

$db_fields = array(
									'client_id' => 'Client ID',
									'emp_id' => 'Employee',
									'client_name' => 'Client Name',
									'client_address' => 'Address',
									'client_address2' => 'Address 2',
									'client_city' => 'City',
									'client_state' => 'State',
									'client_zip' => 'Zip',
									'client_phone' => 'Phone',
									'client_phone2' => 'Alt. Phone',
									'client_contact' => 'Contact Name',
									'client_is_business' => 'Business',
									'client_is_tangible' => 'Tangible',
									'client_is_individual' => 'Individual',
									'client_is_newsletter' => 'Newsletter',
									'client_is_xmas' => 'Christmas',
									'client_is_payroll' => 'Payroll',
									'client_is_intangible' => 'Intangible',
									'client_is_reviewed' => 'Reviewed',
									'client_yearend' => 'Year End',
									'client_notes' => 'Notes',
									'client_status' => 'Status',
									'client_type' => 'Type'
									//'client_date_entered' => 'Entered',
									//'client_date_modified' => 'Last Modified'
										);



$db = new client();
$form = new form();

// EDIT RECORD
	if($_POST['action']=="edit"){
		$_POST['new_action'] = "edit_submit";
		$db_rows = $db->getSingle($_POST['id']);
		if (mysql_num_rows($db_rows) > 0) {
			$db_row = mysql_fetch_assoc($db_rows);
			foreach($db_fields As $k => $v){
				$_POST[$k] = $db_row[$k];
			}
		}

// ADD RECORD
	}elseif($_POST['action']=="add"){
		$_POST['new_action'] = "add_submit";

// EDIT FORM POSTED
	}elseif($_POST['action']=="edit_submit"){
		$_POST['new_action'] = "edit_submit";
		$post_array = makePostArray($db_fields);
		$db_rows = $db->update($post_array,$_POST['id']);
		$message = "This record has been updated sucessfully.";

// ADD FORM POSTED
	}elseif($_POST['action']=="add_submit"){
		$_POST['new_action'] = "edit_submit";
		$post_array = makePostArray($db_fields);
		$db_rows = $db->insert($post_array);
		$message = "This record has been added sucessfully.";
	}
?>

<div id="intQuad">
	<div class="button"><a href="client_modify.php?action=add">CREATE NEW CLIENT</a></div>
	<h2>CLIENT</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner">
				<div style="min-height:300px;vertical-align:middle;text-align:center;">


<div align="center" style="color:green;"><?php echo $message; ?></div>
<br />
					<form method="post" action="<?php echo script_name(); ?>" name="form1" id="form1" enctype="multipart/form-data">
					<table class="formtable" cellpadding="0" cellspacing="0">
<?php
		foreach($db_fields As $k => $v){
			if($k=="emp_id"){
				$inputHTML = $form->getEmpSelect($_POST['emp_id']);
			//}elseif($k=="client_id"){
			//	$inputHTML = $form->getClientSelect($_POST['client_id']);
			}elseif($k=="svc_id"){
				$inputHTML = $form->getSvcSelect($_POST['svc_id']);
			}elseif($k=="client_notes"){
				$inputHTML = "<textarea class=\"alt\" id=\"".$k."\" name=\"".$k."\">".$_POST[$k]."</textarea>";
			}elseif($k=="client_status"){
				$inputHTML = $form->getStatusSelect($k,$_POST[$k]);
			}elseif(substr($k,0,9)=="client_is"){
				$inputHTML = $form->getYesNoSelect($k,$_POST[$k]);
			}elseif($k=="client_type"){
				$inputHTML = $form->getClientTypeSelect($k,$_POST[$k]);
			}else{
				$inputHTML = "<input class=\"alt\" type=\"text\" name=\"".$k."\" id=\"".$k."\" value=\"".$_POST[$k]."\" />";
			}
?>
					<tr valign="top">
						<td class="inputLabel" <?php errs($errors[$k]); ?> ><?php echo $v; ?></td>
						<td class="inputField"><?php echo $inputHTML; ?></td>
					</tr>
<?php
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
			
<script type="text/javascript">
$(document).ready(function(){
$("#client_yearend").datepicker({
		defaultDate: new Date(<?php echo $_POST['client_yearend']; ?>),
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