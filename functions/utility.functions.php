<?php

require_once(SITE_PATH."\\includes\\constants.php");


function viewGetPost(){
	$results = "<div style=\"font-size:8px;color:#ccc;border:dotted 1px #eee; margin:10px;padding:7px;\">";
	if(isset($_POST)){
		foreach($_POST as $k => $v){
			$results .= "POST: ".$k.": ".$v."<br />\r\n";
		}
	}
	if(isset($_GET)){
		foreach($_GET as $k => $v){
			$results .= "GET: ".$k.": ".$v."<br />\r\n";
		}
	}
	$results.="</div>";
	return $results;
}



//########################################
// CLEAN STRING TO PREPARE FOR MYSQL DB 
function dbClean($str){
	if(isset($str) && $str!=""){
		$results = addslashes($str);
	}
	return $results;
}
//########################################




function IsDate( $Str )
{
  $Stamp = strtotime( $Str );
  $Month = date( 'm', $Stamp );
  $Day   = date( 'd', $Stamp );
  $Year  = date( 'Y', $Stamp );

  return checkdate( $Month, $Day, $Year );
}

function getDateArray( $Str )
{
  $Stamp = strtotime( $Str );
  $Month = date( 'm', $Stamp );
  $Day   = date( 'd', $Stamp );
  $Year  = date( 'Y', $Stamp );
	
	$result = array('month' => $Month, 'day' => $Day, 'year' => $Year);
	
  return $result;
}

function getDatePeriod($d=''){
if($d=='') $d = date('Y-m-d');
$dateArray = getDateArray($d);
if($dateArray['day'] > 15){
	$results =  date('Y-m-d', mktime(0, 0, 0, ($dateArray['month']+1),0, $dateArray['year']));
}else{
	$results =  date('Y-m-d', mktime(0, 0, 0, $dateArray['month'],15, $dateArray['year']));
}
	return $results;
}

function lastday($month = '', $year = '') {
   if (empty($month)) $month = date('m');
   if (empty($year)) $year = date('Y');
   return date('Y-m-d', mktime(0, 0, 0, $month, 0, $year));
}











function script_name(){
	$file = $_SERVER["SCRIPT_NAME"];
	$break = Explode('/', $file);
	$pfile = $break[count($break) - 1];
	return $pfile;
}
function format_date($original='', $format="%m/%d/%Y") {
    $format = ($format=='small' ? "%m-%d-%y" : $format);
    $format = ($format=='date' ? "%m-%d-%Y" : $format);
    $format = ($format=='datetime' ? "%m-%d-%Y %I:%M %p" : $format);
    $format = ($format=='mysql-date' ? "%Y-%m-%d" : $format);
    $format = ($format=='mysql-datetime' ? "%Y-%m-%d %H:%M:%S" : $format);
    return (!empty($original) ? strftime($format, strtotime($original)) : "" );
} 

function select_list($list, $name, $option = false, $select = ""){
	$sl = "<select name=\"$name\" class=\"alt\" style=\"float:none;width:auto;\">";
	if ($option)
		$sl .= "<option value=\"\"> -- Select One --";
	foreach ($list as $k => $v){
		$selected = ($k == $select) ? "selected" : "";
		$sl .= "<option value=\"$k\" $selected>$v";
	}
	$sl .= "</select>";
	return $sl;
}
function checkbox_list($list, $name, $checklist=""){
		$cb = "<div class=\"checkbox_input\" >\n";
		foreach ($list as $k => $v){
			if(sizeof($checklist)!=0){
				if(in_array($k,$checklist)){
					$checked = " checked";
				}else{
					$checked = "";
				}
			}else{
				$checked = "";
			}
			$cb .= "<input type=\"checkbox\" name=\"".$name."_list\" value=\"".$k."\"$checked /> &nbsp; ".$v."<br />\n";
		}
		$cb .= "<input type=\"hidden\" name=\"".$name."\" id=\"".$name."\" value=\"\" />\n";
		$cb .= "</div>\n";
		return $cb;
	}
	
function prepare_params($values){
	foreach ($values as $k => $v)
		$values[$k] =  prepare_param($v);
	return $values;
}

function prepare_update_params($values){
	foreach ($values as $k => $v)
		$values[$k] =  "$k=" . prepare_param($v);
	
	return $values;
}

function prepare_param($value){
	return "'" . addslashes($value) . "'";
}

function ToMysqlDate($date) {
   $dt[0] = substr($Tstamp,0,4);
   $dt[1] = substr($Tstamp,4,2);
   $dt[2] = substr($Tstamp,6,2);
   $tm[0] = substr($Tstamp,8,2);
   $tm[1] = substr($Tstamp,10,2);
   $tm[2] = substr($Tstamp,12,2);
   return (join($dt,"-") . " " . join($tm,":"));
}

function delArrayElementByKey($array_with_elements, $key_name) {
   $key_index = array_keys(array_keys($array_with_elements), $key_name);
   if (count($key_index) != '') {
       array_splice($array_with_elements, $key_index[0], 1);
   }
   return $array_with_elements;
}

function toQueryString($hash){
	$qsArr = array();
	foreach ($hash as $k => $v){
		$qsArr[] = "$k=" . urlencode($v);
	}
	return "?" . implode($qsArr,"&");
}

function formatPhoneNumber($strPhone)
{
	$strPhone = ereg_replace("[^0-9]",'', $strPhone);
	if (strlen($strPhone) != 10)
	{
			return false;
	}
	$strArea = substr($strPhone, 0, 3);
	$strPrefix = substr($strPhone, 3, 3);
	$strNumber = substr($strPhone, 6, 4);
	$strPhone = "(".$strArea.") ".$strPrefix."-".$strNumber;
	return ($strPhone);
}

function checkEmail($email)
{
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function sel($a,$b)	{
	if($a == $b) print(' selected="selected" ');
}

function checked($a,$b)	{
	if($a == $b) print(' checked ');
}

function errs($field)	{
	if($field) { 
		print('style="color: #cc0000;"');
	} 
}

function getMySQLDate($mydate){
	$date = explode("/",$mydate);
	if (!$date)
		$date = explode("-",$date);
		
	if ($date != false){
		if (checkdate($date[0], $date[1], $date[2]))
		{
			return $date[2] . "-" . $date[0] . "-" . $date[1] . " 00:00:00";
		}
		else {
			return "";
		}
	} else {
		return "";
	}
}

function switchDir($odir){
	if($odir=="asc"){
		return "desc";
	}else{
		return "asc";
	}
}

function prepareDisplayedText($text)
{
	$string = $text;
	$string = preg_replace('#(^|\s)([a-z]+://([^\s\w/]?[\w/])*)#is', '\\1<a href="\\2" target="_new">\\2</a>', $string);
	$string = preg_replace('#(^|\s)((www|ftp)\.([^\s\w/]?[\w/])*)#is', '\\1<a href="http://\\2" target="_new">\\2</a>', $string);
	$string = preg_replace('#(^|\s)(([a-z0-9._%+-]+)@(([.-]?[a-z0-9])*))#is', '\\1<a href="mailto:\\2">\\2</a>', $string);
	$string = str_replace("\r\n", "<br>", $string);
	return $string;
}

function makePostArray($fields){
	$array = array();
	foreach($fields As $k => $v){
		$array[$k] = $_POST[$k];
	}
	return $array;
}
function killPostArray($fields){
	foreach($fields As $k => $v){
		unset($_POST[$k]);
		//$_POST[$k] = "";
	}
}

function getAvatarSrc($icon_filename=""){
	if($icon_filename=="") $icon_filename=$_SESSION['emp_icon'];
	if($icon_filename=="") $icon_filename=AVATAR_DEFAULT_IMG;
	return SITE_URL."/".AVATAR_PATH."/".$icon_filename;
}

function getUserFullname(){
	$s = "";
	if(isset($_SESSION['emp_firstname'])){
		$s = ucwords($_SESSION['emp_firstname']." ".$_SESSION['emp_lastname']);
	}else{
		$s = "";
	}
	if(!trim($s)==""){
		return $s;
	}else{
		return "Guest User";
	}
}
function shortString($s='',$i=20){
	$s = trim($s);
	if(strlen($s)>$i){
		$s = substr($s, 0, $i)."...";
	}
	return $s;
}

setlocale(LC_MONETARY, 'en_US');

function makeMoney($number){
	return "".makeCurrency($number);
}

function makeCurrency($number){
	//return money_format('%(#10n', $number) . "\n";
	$c = number_format($number,2);
	if($c<0){
		$c="<span style=\"color:red;\">".$c."</span>";
	}else{
		
	}
	return $c;
}
function makeCheckBox($name="", $val="", $onclick=""){
	return "<input type=\"checkbox\" name=\"".$name."\" id=\"".$name."\" value=\"".$val."\" onclick=\"".$onclick."\" />\r\n";
}
?>