<?php
ob_start();
session_start();

require_once(SITE_PATH."\\functions\\utility.functions.php");

class Authentication
{
	var $loginPage = "login.php";
	
	function Authentication(){}
	
	function authenticateUser($un, $pw){
		$sq = "select * from emp where emp_login = " . prepare_param($un) . " and emp_password = " . prepare_param($pw) . "; ";
		$result = mysql_query($sq) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {
			$auth = true;
			if ($row = mysql_fetch_assoc($result)){
				$emp_id 						= $row['emp_id'];
				$emp_firstname 			= $row['emp_firstname'];
				$emp_lastname 			= $row['emp_lastname'];
				$emp_nickname 			= $row['emp_nickname'];
				$emp_email 					= $row['emp_email'];
				$emp_default_chrg 	= $row['emp_default_chrg'];
				$emp_icon 					= $row['emp_icon'];
			}
		}
		else {
			$auth = false;
			$eid = 0;
		}
		$this->setAuthenticated($auth,$emp_id,$emp_firstname,$emp_lastname,$emp_nickname,$emp_email,$emp_default_chrg,$emp_icon);
		return $auth;
	}
	
	function setAuthenticated($auth,$emp_id,$emp_firstname,$emp_lastname,$emp_nickname,$emp_email,$emp_default_chrg,$emp_icon){
		if ($auth == true){
			$_SESSION["emp_id"] 						= $emp_id;
			$_SESSION["emp_firstname"] 			= $emp_firstname;
			$_SESSION["emp_lastname"] 			= $emp_lastname;
			$_SESSION["emp_nickname"] 			= $emp_nickname;
			$_SESSION["emp_email"] 					= $emp_email;
			$_SESSION["emp_default_chrg"] 	= $emp_default_chrg;
			$_SESSION["emp_icon"] 					= $emp_icon;
		} else{
			$_SESSION["emp_id"] 						= "";
			$_SESSION["emp_firstname"] 			= "";
			$_SESSION["emp_lastname"] 			= "";
			$_SESSION["emp_nickname"] 			= "";
			$_SESSION["emp_email"] 					= "";
			$_SESSION["emp_default_chrg"] 	= "";
			$_SESSION["emp_icon"] 					= "";

			unset($_SESSION["emp_id"]);
			unset($_SESSION["emp_firstname"]);
			unset($_SESSION["emp_lastname"]);
			unset($_SESSION["emp_nickname"]);
			unset($_SESSION["emp_email"]);
			unset($_SESSION["emp_default_chrg"]);
			unset($_SESSION["emp_icon"]);
		}
	}
	
	function getAuthenticated(){
		if (isset($_SESSION["emp_id"])){
			 return true;
		}else {
			return false;
		}
	}


	function authenticatePage(){
		if (!$this->getAuthenticated()){
			$this->setAuthenticated(false,"","","","","","","");
			if (script_name() != $this->loginPage)
				header("Location: " . $this->loginPage);
		}
	}
	
}
?>