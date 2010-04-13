<?php

error_reporting (E_ALL ^ E_NOTICE);
setlocale(LC_MONETARY, 'en_US');


include_once("../includes/constants.php");
include_once("../functions/utility.functions.php");
include_once("../includes/db.php");
include_once("../classes/tslip.class.php");

$html = "";

$tbl = $_REQUEST['tbl'];


$db = new tslip();

/*
if($_REQUEST['action']=="delete" && isset($_REQUEST['id']) && isset($_REQUEST['table'])){	$result = $db->delete($_REQUEST['id'],$_REQUEST['table']);}

if($_REQUEST['action']=="clear" && !empty($_REQUEST['ids'])){
	$ids = trim($_REQUEST['ids'],",");
	$a = array("tslip_status"=>STATUS_CLEARED);
	if($db->update_all_in($a,$ids)){
		$html .= "Records cleared sucessfully!";
	}else{
		$html .= "There was an error.";
	}
}
*/

if($_REQUEST['action']=="deleteSelected" && !empty($_REQUEST['ids'])){
	$ids = trim($_REQUEST['ids'],",");
	$a = array("tslip_status"=>STATUS_DELETED);
	if($db->delete_slips_in($ids)){
		$html .= "Records deleted sucessfully!";
	}else{
		$html .= "There was an error.";
	}
}


echo $html;
?>
