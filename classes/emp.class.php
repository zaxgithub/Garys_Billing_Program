<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");

class emp
{
	function emp(){}


	function getAll($start=0, $end=10, $orderby="emp_id", $dir="asc"){
		$sq = "SELECT * FROM emp 
						order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	function getAllSimpleSearch($start=0, $end=10, $orderby="emp_id", $dir="asc", $search_term=""){
		$sq = "SELECT * FROM emp".
						" where".
						" emp_firstname LIKE '%".$search_term."%'".
						" OR emp_lastname LIKE '%".$search_term."%'".
						" OR emp_id LIKE '%".$search_term."%'".
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	function getAllTotal(){
		$sq = "SELECT COUNT(*) FROM emp";
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		$numrows = $query_data[0];
		return $numrows;
	}
	function getNameById($id){
		$sq = "select emp_nickname from emp where emp_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		return $query_data[0];
	}
	function getRateById($id){
		$sq = "select emp_default_chrg from emp where emp_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		return $query_data[0];
	}
	function getSingle($id){
		$sq = "select * from emp where emp_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function update($col_hash, $id){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash = prepare_update_params($col_hash);
		$sq = "update emp set " . implode(",",$col_hash) . " " . 
					"where emp_id = " . prepare_param($id);
		return mysql_query($sq) or die(mysql_error());
	}

	function insert($col_hash){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash = prepare_params($col_hash);
		$sq = "insert into emp (" . implode(",",array_keys($col_hash)) .") " .
					"values (" . implode(",",$col_hash) . ")";
		mysql_query($sq) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function delete($id){
		$sq = "delete from emp where emp_id = " . prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	
	
}



?>