<?php
// DATABASE
define("DB_NAME", "gary");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "sc0rpi0");

define("WP_PASSWORD", ")UIUXpF*@Sjz");


// LOCATION
define("SITE_URL", "http://".$_SERVER['HTTP_HOST']."");
define("SITE_PATH", "C:\\xampp\\htdocs");

define("SITE_BACKUP_FOLDER", realpath(dirname(__FILE__)));



define("DASHBOARD_ICON_PATH", "images/dashboard");//no trailing slash

define("AVATAR_PATH", "images/avatars");//no trailing slash
define("AVATAR_DEFAULT_IMG", "default.png");//no trailing slash


// ADMIN
define("ADMIN_EMAIL", "zaxnet@gmail.com");
define("ADMIN_NAME", "Zack");

// DEFAULT INFO
define("DEFAULT_TITLE", "ARENSON &amp; SANDHOUSE :: FLEX SYSTEM");
define("DEFAULT_ROWS_PER_PAGE", 100);//In the 'record view' pages will show this many records on a single pages

// OTHER CONSTANTS
define("CURRENT_MYSQL_TIMESTAMP",date('Y-m-d H:i:s'));


// STATUS IDs
//define("STATUS_",);
define("STATUS_INACTIVE",0);
define("STATUS_ACTIVE",1);
define("STATUS_PENDING",79);// FOR BILLING AND TIME SLIPS THAT HAVE NOT YET BEEN SAVED. THE "QUEUE".
define("STATUS_CLEARED",89);// FOR CLEARING A SET OF BILLS
define("STATUS_DELETED",99);// FOR DELETED ITEMS

/*
// SESSION VARS

			// /classes/auth.class.php
			$_SESSION["emp_id"] 						= "";
			$_SESSION["emp_firstname"] 			= "";
			$_SESSION["emp_lastname"] 			= "";
			$_SESSION["emp_nickname"] 			= "";
			$_SESSION["emp_email"] 					= "";
			$_SESSION["emp_default_chrg"] 	= "";
			$_SESSION["emp_icon"] 					= "";
			
			// /includes/view_search.php
			$_SESSION["period_ending"] 					= "";
*/
?>
