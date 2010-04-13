<?php 
header("Cache-Control: no-cache, must-revalidate");   // HTTP/1.1
header("Expires: Jan 1, 2000");   // Date in the past

require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\form.class.php");

$form = new form();
$_POST['svc_id'] == "null" ? $svc_id = "" : $svc_id = $_POST['svc_id'];
$_POST['emp_id'] == "null" ? $emp_id = "" : $emp_id = $_POST['emp_id'];
$_POST['tslip_hours'] == "null" ? $tslip_hours = "" : $tslip_hours = $_POST['tslip_hours'];


echo $form->getTslipPrice($svc_id,$emp_id,$tslip_hours);
?>