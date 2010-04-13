<?php 
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\auth.class.php");
$auth = new Authentication();
$auth->setAuthenticated(false,"","","","","");
$auth->authenticatePage();

?>
