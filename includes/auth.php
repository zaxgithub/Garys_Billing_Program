<?php
require_once(SITE_PATH."\\classes\\auth.class.php");
$auth = new Authentication();
$auth->authenticatePage(); 
?>