<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");

class tslip
{
	function tslip(){}

	function getAll($start=0, $end=10, $orderby="tslip_id", $dir="asc"){
		$sq = "SELECT t.*, e.emp_nickname, CONCAT(c.client_name,' :: ',c.client_contact) as client_id, CONCAT(s.svc_code,' :: ',s.svc_name) as svc_id  FROM tslip t".
						" inner join emp e on t.emp_id = e.emp_id".
						" inner join client c on t.client_id = c.client_id".
						" inner join svc s on t.svc_id = s.svc_id".
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	
	function getAllPending($eid){
		$sq = "SELECT t.*, emp_nickname, CONCAT(c.client_id,' : ',c.client_name) as client_id, CONCAT(s.svc_code,' : ',s.svc_name) as svc_id  FROM tslip t".
						" inner join emp e on t.emp_id = e.emp_id".
						" inner join client c on t.client_id = c.client_id".
						" inner join svc s on t.svc_id = s.svc_id".
						" where".
						" t.tslip_emp_modified = " . $eid .
						" AND t.tslip_status = " . STATUS_PENDING .
						" order by t.tslip_id DESC";
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function getAllSimpleSearch($start=0, $end=10, $orderby="tslip_id", $dir="asc", $search_term=""){
		$sq = "SELECT t.*, emp_nickname, CONCAT(c.client_id,' : ',c.client_name) as client_id, CONCAT(s.svc_code,' : ',s.svc_name) as svc_id  FROM tslip t".
						" inner join emp e on t.emp_id = e.emp_id".
						" inner join client c on t.client_id = c.client_id".
						" inner join svc s on t.svc_id = s.svc_id".
						" where".
						" (e.emp_firstname LIKE '%".$search_term."%'".
						" OR e.emp_lastname LIKE '%".$search_term."%'".
						" OR c.client_name LIKE '%".$search_term."%'".
						" OR c.client_contact LIKE '%".$search_term."%'".
						" OR s.svc_name LIKE '%".$search_term."%'".
						") AND t.tslip_status < ".STATUS_PENDING.
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function getAllTotal($search_term=""){
		$sq = "SELECT COUNT(*) FROM tslip t".
						" inner join emp e on t.emp_id = e.emp_id".
						" inner join client c on t.client_id = c.client_id".
						" inner join svc s on t.svc_id = s.svc_id".
						" where".
						" (e.emp_firstname LIKE '%".$search_term."%'".
						" OR e.emp_lastname LIKE '%".$search_term."%'".
						" OR c.client_name LIKE '%".$search_term."%'".
						" OR c.client_contact LIKE '%".$search_term."%'".
						" OR s.svc_name LIKE '%".$search_term."%'".
						") AND t.tslip_status < ".STATUS_PENDING;
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		$numrows = $query_data[0];
		return $numrows;
	}



	function getSingle($id){
		$sq = "select * from tslip where tslip_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function update($col_hash, $id){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['tslip_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['tslip_emp_modified'] = $_SESSION['emp_id'];
		$col_hash = prepare_update_params($col_hash);
		$sq = "update tslip set " . implode(",",$col_hash) . " " . 
					"where tslip_id = " . prepare_param($id);
		return mysql_query($sq) or die(mysql_error());
	}

	function insert($col_hash){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['tslip_date_entered'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['tslip_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['tslip_emp_modified'] = $_SESSION['emp_id'];
		$col_hash['tslip_status'] = STATUS_PENDING;		
		$col_hash = prepare_params($col_hash);
		$sq = "insert into tslip (" . implode(",",array_keys($col_hash)) .") " .
					"values (" . implode(",",$col_hash) . ")";
		mysql_query($sq) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function delete($id){
		$sq = "delete from tslip where tslip_id = " . prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function setCookies(){
		$_COOKIE['tslip_period_ending'] = $_POST['tslip_period_ending'];
		setcookie('tslip_period_ending',$_POST['tslip_period_ending'],(time()+2595000),'/');
		$_COOKIE['tslip_emp_id'] = $_POST['emp_id'];
		setcookie('tslip_emp_id',$_POST['emp_id'],(time()+2595000),'/');
	}



	function saveSlipsByEmp($emp_id,$date_now,$status){
		$bslip_hash = array();

		//$tslip_hash = prepare_params($bslip_hash);
		$sq = "update tslip set ".
					"tslip_status=" . $status . ", ".
					"tslip_date_modified='" . $date_now . "', ".
					"tslip_emp_modified=" . $emp_id . " ".
					"where tslip_emp_modified = ".$_SESSION['emp_id'];
		return $tmp = mysql_query($sq) or die(mysql_error());
	}

	function save_slips_in($ids=""){
		if(!empty($ids)){
			$status=1;//"STATUS_ACTIVE1"
			$date_now = date('Y-m-d H:i:s');
			$emp_id = $_SESSION['emp_id'];
			//$tslip_hash = prepare_params($bslip_hash);
			$sq = "update tslip set ".
						"tslip_status=" . $status . ", ".
						"tslip_date_modified='" . $date_now . "', ".
						"tslip_emp_modified=" . $emp_id . " ".
						"where tslip_id IN(".trim($ids,",").")";
			return $tmp = mysql_query($sq) or die(mysql_error());
		}else{
			return false;
		}
	}

	function delete_slips_in($ids=""){
		if(!empty($ids)){
			$bslip_hash = array();
			$status=99;//"STATUS_DELETED"
			$date_now = date('Y-m-d H:i:s');
			//$emp_id = $_SESSION['emp_id'];
			//$tslip_hash = prepare_params($bslip_hash);
			$sq = "update tslip set ".
						"tslip_status=" . $status . ", ".
						//"tslip_date_modified='" . $date_now . "', ".
						//"tslip_emp_modified=" . $emp_id . " ".
						
						"tslip_date_modified='" . $date_now . "' ".
						
						"where tslip_id IN(".trim($ids,",").")";
			return $tmp = mysql_query($sq) or die(mysql_error());
		}else{
			return false;
		}
	}


	
}



?>