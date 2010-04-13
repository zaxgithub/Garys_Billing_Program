<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");

class bslip
{
	function bslip(){}


	function getAll($start=0, $end=10, $orderby="bslip_id", $dir="asc"){
		$sq = "SELECT b.*, CONCAT(e.emp_firstname,' ',e.emp_lastname) as emp_id, CONCAT(c.client_name,' :: ',c.client_contact) as client_id  FROM bslip b".
						" inner join emp e on b.emp_id = e.emp_id".
						" inner join client c on b.client_id = c.client_id".
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function getAllSimpleSearch($start=0, $end=10, $orderby="bslip_id", $dir="asc", $search_term=""){
		$sq = "SELECT b.*,if(bslip_status=1,'Active','Inactive') as bslip_status_name, e.emp_id, e.emp_nickname, CONCAT(c.client_id,' : ',c.client_name) as client_id FROM bslip b".
						" inner join emp e on b.emp_id = e.emp_id".
						" inner join client c on b.client_id = c.client_id".
						" where".
						" (e.emp_firstname LIKE '%".$search_term."%'".
						" OR e.emp_lastname LIKE '%".$search_term."%'".
						" OR c.client_name LIKE '%".$search_term."%'".
						" OR c.client_contact LIKE '%".$search_term."%'".
						") AND b.bslip_status < ".STATUS_PENDING.
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	
	
	
	function getAllPending($eid){
		$sq = "SELECT b.*,if(bslip_status=1,'Active','Inactive') as bslip_status_name, e.emp_id, e.emp_nickname, CONCAT(c.client_id,' : ',c.client_name) as client_id FROM bslip b".
						" inner join emp e on b.emp_id = e.emp_id".
						" inner join client c on b.client_id = c.client_id".
						" where".
						" b.bslip_emp_modified = " . $eid .
						" AND b.bslip_status = " . STATUS_PENDING .
						" order by b.bslip_id DESC";
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	

	function getAllTotal($search_term=""){
		$sq = "SELECT COUNT(*) FROM bslip b".
						" inner join emp e on b.emp_id = e.emp_id".
						" inner join client c on b.client_id = c.client_id".
						" where".
						" (e.emp_firstname LIKE '%".$search_term."%'".
						" OR e.emp_lastname LIKE '%".$search_term."%'".
						" OR c.client_name LIKE '%".$search_term."%'".
						" OR c.client_contact LIKE '%".$search_term."%'".
						") AND b.bslip_status < ".STATUS_PENDING;
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		$numrows = $query_data[0];
		return $numrows;
	}
	
	
	function getSingle($id){
		$sq = "select * from bslip where bslip_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function update($col_hash, $id){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['bslip_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['bslip_emp_modified'] = $_SESSION['emp_id'];
		$col_hash = prepare_update_params($col_hash);
		$sq = "update bslip set " . implode(",",$col_hash) . " " . 
					"where bslip_id = " . prepare_param($id);
		return mysql_query($sq) or die(mysql_error());
	}

	function insert($col_hash){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['bslip_date_entered'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['bslip_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['bslip_emp_modified'] = $_SESSION['emp_id'];
		$col_hash['bslip_status'] = STATUS_PENDING;		
		$col_hash = prepare_params($col_hash);
		$sq = "insert into bslip (" . implode(",",array_keys($col_hash)) .") " .
					"values (" . implode(",",$col_hash) . ")";
		mysql_query($sq) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function delete($id){
		$sq = "delete from bslip where bslip_id = " . prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	

	function setCookies(){
		$_COOKIE['bslip_date_worked'] = $_POST['bslip_date_worked'];
		setcookie('bslip_date_worked',$_POST['bslip_date_worked'],(time()+2595000),'/');
		$_COOKIE['bslip_emp_id'] = $_POST['emp_id'];
		setcookie('bslip_emp_id',$_POST['emp_id'],(time()+2595000),'/');
	}



	function saveSlipsByEmp($emp_id,$date_now,$status){
		$bslip_hash = array();

		//$bslip_hash = prepare_params($bslip_hash);
		$sq = "update bslip set ".
					"bslip_status=" . $status . ", ".
					"bslip_date_modified='" . $date_now . "', ".
					"bslip_emp_modified=" . $emp_id . " ".
					"where bslip_emp_modified = ".$_SESSION['emp_id'];
		return $tmp = mysql_query($sq) or die(mysql_error());
	}




}



?>