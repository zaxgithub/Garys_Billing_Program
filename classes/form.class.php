<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");
class form
{
	function form(){}

	function getEmpSelect($selected=""){
		$list = array();
		$sq = "select emp_id, emp_firstname, emp_lastname from emp " .
					//"where affiliate_status > 0 " .
					"ORDER BY emp_id ASC ";
		$result = mysql_query($sq) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{ 
			$list[$row['emp_id']] = ucwords($row['emp_id']." : ".$row['emp_firstname']." ".$row['emp_lastname']);
		}
		$sel_list = select_list($list,"emp_id",true,$selected);
		return $sel_list;
	}
	
	function getEmpLookup($s=""){
		$json = "{\"results\":[\n";
		$sq = "select emp_id, emp_firstname, emp_lastname from emp " .
					"where emp_id LIKE '".$s."%' " .
					"OR emp_firstname LIKE '%".$s."%' " .
					"OR emp_lastname LIKE '%".$s."%' " .
					"ORDER BY emp_id ASC ";
		$result = mysql_query($sq) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{ 
			$json .= "{\"id\":".$row['emp_id'].",\"name\":\"".ucwords($row['emp_id']." : ".$row['emp_firstname']." ".$row['emp_lastname'])."\"},\n";
		}
		$json .= "{\"id\":\"\",\"name\":\"\"}";
		$json .= "]}";
		return $json;
	}

	function getClientLookup($s=""){
		$json = "{\"results\":[\n";
		$sq = "select client_id, client_name, client_contact from client " .
					"where client_id LIKE '".$s."%' " .
					//"OR client_name LIKE '%".$s."%' " .
					//"OR client_contact LIKE '%".$s."%' " .
					"ORDER BY client_id ASC ";
		$result = mysql_query($sq) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{ 
			$json .= "{\"id\":".$row['client_id'].",\"name\":\"".ucwords($row['client_id']." : ".$row['client_name']." ".$row['client_contact'])."\"},\n";
		}
		$json .= "{\"id\":\"\",\"name\":\"\"}";
		$json .= "]}";
		return $json;
	}

	function getSvcLookup($s=""){
		$json = "{\"results\":[\n";
		$sq = "select svc_id, svc_name, svc_code from svc " .
					"where svc_code LIKE '".$s."%' " .
					"ORDER BY svc_code ASC ";
		$result = mysql_query($sq) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{ 
			$json .= "{\"id\":".$row['svc_id'].",\"name\":\"".ucwords($row['svc_code']." : ".$row['svc_name'])."\"},\n";
		}
		$json .= "{\"id\":\"\",\"name\":\"\"}";
		$json .= "]}";
		return $json;
	}


	
	function getClientSelect($selected=""){
		$list = array();
		$sq = "select client_id, client_name, client_contact from client " .
					"where client_status > 0 " .
					"ORDER BY client_id ASC ";
		$result = mysql_query($sq) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{ 
			$list[$row['client_id']] = ucwords($row['client_id']." : ".$row['client_name']);
		}
		$sel_list = select_list($list,"client_id",true,$selected);
		return $sel_list;
	}
	function getSvcSelect($selected=""){
		$list = array();
		$sq = "select svc_id, svc_name, svc_code from svc " .
					"where svc_status > 0 " .
					"ORDER BY svc_id ASC ";
		$result = mysql_query($sq) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{ 
			$list[$row['svc_id']] = ucwords($row['svc_code']." :: ".$row['svc_name']);
		}
		$sel_list = select_list($list,"svc_id",true,$selected);
		return $sel_list;
	}

	function getStatusSelect($fieldname,$selected=1){
		if($selected=="") $selected=1;
		$list = array();
		$list[STATUS_ACTIVE] = "Active";
		$list[STATUS_INACTIVE] = "Inactive";
		$list[STATUS_PENDING] = "Pending";
		$list[STATUS_CLEARED] = "Cleared";
		$sel_list = select_list($list,$fieldname,true,$selected);
		return $sel_list;
	}

	function getYesNoSelect($fieldname,$selected=""){
		if($selected=="") $selected=1;
		$list = array();
		$list[0] = "No";
		$list[1] = "Yes";
		$sel_list = select_list($list,$fieldname,false,$selected);
		
		return $sel_list;
	}

	function getClientTypeSelect($fieldname="client_type",$selected=""){
		$list = array();
		$list['COR'] = "COR";
		$list['INDI'] = "INDI";
		$list['PART'] = "PART";
		$list['TRST'] = "TRST";
		$list['LLC'] = "LLC";
		$list['NONP'] = "NONP";
		$sel_list = select_list($list,$fieldname,true,$selected);
		return $sel_list;
	}


function empChrgField($eid){
	$fieldname = "svc_chg_default";
	switch($eid){
		case 1:
			$fieldname = "svc_chg_gary";
			break;
		case 2:
			$fieldname = "svc_chg_glenda";
			break;
		case 4:
			$fieldname = "svc_chg_jenifer";
			break;
		case 100:
			$fieldname = "svc_chg_default";
			break;
		default:
			$fieldname = "svc_chg_default";
			break;
	}
	return $fieldname;
}


	function getTslipPrice($svc_id="",$emp_id="",$tslip_hours=""){
		if ($svc_id!="" && $emp_id!="" && $tslip_hours!="") {

			$sq = "SELECT s.*, e.emp_id, e.emp_default_chrg FROM svc s, emp e WHERE e.emp_id = ".$emp_id." AND s.svc_id = ".$svc_id;
			$result = mysql_query($sq) or die(mysql_error());

			while ($row = mysql_fetch_array($result)){ 
				$svcCharge = $row[$this->empChrgField($emp_id)];
				if($svcCharge == 0.00){
					$price = $row['emp_default_chrg'];
				}else{
					$price = $svcCharge;
				}
			}
			if($tslip_hours==""){ $tslip_hours = 0; }
			return $price * $tslip_hours;
		}else{
			return "0.00";
		}

	}
	
	
	
}
?>