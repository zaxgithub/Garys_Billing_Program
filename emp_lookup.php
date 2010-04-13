<?php 
header("Cache-Control: no-cache, must-revalidate");   // HTTP/1.1
header("Expires: Jan 1, 2000");   // Date in the past

require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\form.class.php");

$form = new form();
$_GET['q'] == "null" ? $srch = "" : $srch = $_GET['q'];
echo $form->getEmpLookup($srch);
?>