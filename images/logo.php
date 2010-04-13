<?php
$site_root = "D:\Inetpub\wwwroot\cruisesforless.com\www";
include_once($site_root."\\classes\\trackerDB.class.php");


$today = date("m/d/Y");

$tracker = new trackerDB();
$tracker->trackIt($_GET['agent_id'],"search.php");



$image_path = (realpath(dirname($_SERVER['SCRIPT_FILENAME']))) . "\\";
$f_logo = "logo_".$_GET['agent_id'].".gif";
if(file_exists($image_path.$f_logo)){
	$logo_image = $f_logo;
}else{
	$logo_image = "logo_default.gif";
}





//$pic = "http://dev.cruisesforless.com/images/agent_profiles/logo_".$id.".gif";
$pic = "http://".$_SERVER['HTTP_HOST']."/images/agent_profiles/".$logo_image;

header("Content-type:  image/gif");



ob_clean();
flush();
readfile($pic);


?>

