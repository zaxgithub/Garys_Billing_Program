<?php

//Substring of _key will be replaced 
//with key of associatve array to be validated.
//Currently supports required and numeric.
$formats = array("required" => "*_key is required.",
								 "numeric"  => "*_key must be numeric.",
								 "date"     => "*invalid date format.");
								 
								 
//Accepts an associative array.
//Checks each item and returns a multi-dim array of error messages.
//ex. $errors["password"]["required"].
//Currently supports required and numeric validation.
function findErrors($inputs, $required = true, $numeric = false, $date = false){
	global $formats;
	//validate parameters
	$ex = array();
	if (!is_array($inputs)){ array_push($ex,"<b>ERROR: function findErrors expects an array parameter, \$inputs <b/>"); }
	if (sizeof($ex) > 0)
		die(implode("<br/>", $ex));
	//find errors
	$errors = array();
	foreach ($inputs as $k => $v){
		$curr_errors = array();
		if ($required && $v == ""){
			$curr_errors["required"] = formatKey($k,"required");
		}
		if ($numeric && !isnumeric($v))
			$curr_errors["numeric"] = formatKey($k,"numeric");
		if ($date && !validDate($v))
			$curr_errors["date"] = formatKey($k,"date");
		if (sizeof($curr_errors) > 0)
			$errors[$k] = $curr_errors;
	}
	return $errors;
}

function validDate($date){
	$delims = array ("/","-");
	foreach ($delims as $delim){
		if ($exp_date = explode($delim,$date)){
			if (($exp_date[0] < 13) &&
				  ($exp_date[1] < 31) &&
				  ($exp_date[2] < 9999 && $exp_date[2] > 1900)){
				 		if (checkdate($exp_date[0],$exp_date[1],$exp_date[2]))
				 			return true;
				 	 }
		}
	}
	return false;
}

//Applies the given format to the key provided.
//A key named "foo_bar" will formatted as "foo bar" in the error string.
function formatKey($key, $format){
	global $formats;
	return ereg_replace("_key", ereg_replace("_"," ",$key), $formats[$format]);
}

//Returns an HTML label containing the given error 
function errorLabel($error){
	if (strlen($error) > 0) return "<label class=\"error\">$error</label>";
	return "";
}


?>