<?php 
error_reporting (E_ALL);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\mysql_backup.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\client.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");


$db = new client();

if($_POST['action']=="backup"){
	
	
	$bak = $db->backupClients();
	
	
	
}elseif($_POST['action']=="newbackup"){

	//---- The name of the file which you want to save backup file in it.
	//---- If this file does not exist it will be created.(over writed).
	//---- You need to use physical path to this file. e.g. ../data/backup.txt
	$output = "/websites/gary/__backups/zz".date("Ymd").".client.sql";
	
	//---- If this is true, only tables' structure will be stored.
	//---- If this is false, tables' structure and all their data(everything) will be stored.
	$structure_only = false;
	
	//---- instantiating object.
	$backup = new mysql_backup(DB_HOST,DB_NAME,DB_USER,DB_PASS,$output,$structure_only);	
	
	//---- calling the backup method finally creats a file with the name specified in $output
	//     and stors everythig so you can copy the file anywhere you want. This file will be
	//     restored with another method of this class named "restore" that is described in
	//     example-backup.php
	$backup->backup();

}

?>
<script type="text/javascript" language="Javascript">
	function submitPageAction(ACTN){
		document.order_form.action.value = ACTN;
		document.order_form.submit();
	}
</script>
<div id="intQuad">
	<h2>HOME</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner" align="center">
				<div style="min-height:300px;vertical-align:middle;text-align:center;width:auto;" align="center">

<input type="button" value="Backup DB" onclick="submitPageAction('backup');" />
<br />
<input type="button" value="NEW Backup DB" onclick="submitPageAction('newbackup');" />
				
					
					
				</div>
		</div>
	</div>
</div>
			
<?php require_once(realpath(dirname(__FILE__))."\\includes\\order_form.php"); ?>
<?php require_once(realpath(dirname(__FILE__))."\\includes\\footer.php"); ?>