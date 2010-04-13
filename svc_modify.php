<?php 
session_start(); 
ob_start();

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\svc.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");


if(isset($_GET['id'])){$_POST['id']=$_GET['id'];}
if(isset($_GET['action'])){$_POST['action']=$_GET['action'];}

$db_fields = array(
									'svc_code' => 'Code',
									'svc_name' => 'Service Name',
									'svc_chg_default' => 'Default Rate',
									'svc_chg_gary' => 'Gary\'s Rate',
									'svc_chg_glenda' => 'Glenda\'s Rate',
									'svc_chg_jenifer' => 'Jenifer\'s Rate',
										);



$db = new svc();


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
	<div class="button"><a href="svc_modify.php?action=add">CREATE NEW SERVICE</a></div>
	<h2>SERVICE</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner">
				<div style="min-height:300px;vertical-align:middle;text-align:center;">


<div align="center" style="color:green;"><?php echo $message; ?></div>
<br />
					<form method="post" action="<?php echo script_name(); ?>" name="form1" id="form1" enctype="multipart/form-data">
					<table class="formtable" cellpadding="0" cellspacing="0">
<?php
		foreach($db_fields As $k => $v){
?>
					<tr valign="top">
						<td class="inputLabel" <?php errs($errors[$k]); ?> ><?php echo $v; ?></td>
						<td class="inputField"><input class="alt" type="text" name="<?php echo $k; ?>" value="<?php echo $_POST[$k]; ?>" /></td>
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
			
			
<?php 
require_once(realpath(dirname(__FILE__))."\\includes\\footer.php");
?>