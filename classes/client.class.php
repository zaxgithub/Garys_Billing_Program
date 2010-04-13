<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");

class client
{
	function client(){}


	function getAll($start=0, $end=10, $orderby="client_id", $dir="asc"){
		$sq = "SELECT * FROM client 
						order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function getClientName($id){
		$clientname = "[UNKNOWN]";
		if(!empty($id) && is_numeric($id)){
		$sq = "select client_name from client where client_id = ". $id;
		$result = mysql_query($sq) or die(mysql_error());
			while($row = mysql_fetch_row($result)){
				$clientname = ucwords($row[0]);
			}
		}
		return $clientname;
	}
	
	
	function backupClients(){
$tableName  = "client";
$backupFile = "/websites/gary/__backups/".date("Ymd").".client.sql";
$sq      = "SELECT * INTO OUTFILE '".$backupFile."' FROM $tableName";
$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	

	function getPrintResults($orderby="client_id", $dir="asc"){
		$sq = "SELECT client.*,if(client_status=1,'Active','Inactive') as client_status_name FROM client order by ".$orderby." ".$dir;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}



	function getAllSimpleSearch($start=0, $end=10, $orderby="client_id", $dir="asc", $search_term=""){
		$sq = "SELECT client.*,if(client_status=1,'Active','Inactive') as client_status_name FROM client".
						" where".
						" client_name LIKE '%".$search_term."%'".
						" OR client_id LIKE '%".$search_term."%'".
						" OR client_contact LIKE '%".$search_term."%'".
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function getAllTotal($search_term=""){
		$sq = "SELECT COUNT(*) FROM client".
						" where".
						" client_name LIKE '%".$search_term."%'".
						" OR client_id LIKE '%".$search_term."%'".
						" OR client_contact LIKE '%".$search_term."%'";
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		$numrows = $query_data[0];
		return $numrows;
	}
	function is_client_id($id){
		$sq = "select * from client where client_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		if (mysql_num_rows($result) > 0){
			return true;
		}else{
			return false;
		}
	}
	function getSingle($id){
		$sq = "select * from client where client_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function update($col_hash, $id){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['client_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash = prepare_update_params($col_hash);
		$sq = "update client set " . implode(",",$col_hash) . " " . 
					"where client_id = " . prepare_param($id);
		return mysql_query($sq) or die(mysql_error());
	}

	function insert($col_hash){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['client_date_entered'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['client_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash = prepare_params($col_hash);
		$sq = "insert into client (" . implode(",",array_keys($col_hash)) .") " .
					"values (" . implode(",",$col_hash) . ")";
		mysql_query($sq) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function delete($id){
		$sq = "delete from client where client_id = " . prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	

}



?>