<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");

class svc
{
	function svc(){}

	function getSvcCharge($id,$field="svc_chg_default"){
		if(!is_numeric($id)){
			$chrg = 0.00;
		}else{
		$sq = "select ".$field." from svc where svc_id = ". $id;
		$result = mysql_query($sq) or die(mysql_error());
		$row = mysql_fetch_row($result);
			if($row){
			$chrg = $row[0];
			}else{
				$chrg = 0.00;
			}
		}
		return $chrg;
	}
	function getSvcName($id){
		$svcname = "[UNKNOWN]";
		if(!empty($id) && is_numeric($id)){
		$sq = "select svc_name from svc where svc_id = ". $id;
		$result = mysql_query($sq) or die(mysql_error());
			while($row = mysql_fetch_row($result)){
				$svcname = $row[0];
			}
		}
		return $svcname;
	}
	
	function is_svc_id($id){
		$sq = "select * from svc where svc_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		if (mysql_num_rows($result) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function getAll($start=0, $end=10, $orderby="svc_id", $dir="asc"){
		$sq = "SELECT * FROM svc 
						order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function getAllSimpleSearch($start=0, $end=10, $orderby="svc_id", $dir="asc", $search_term=""){
		$sq = "SELECT * FROM svc".
						" where".
						" svc_name LIKE '%".$search_term."%'".
						" OR svc_code LIKE '%".$search_term."%'".
						" order by ".$orderby." ".$dir.
						" limit ".$start.", ".$end;
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}


	function getAllTotal($search_term=""){
		$sq = "SELECT COUNT(*) FROM svc".
						" where".
						" svc_name LIKE '%".$search_term."%'".
						" OR svc_code LIKE '%".$search_term."%'";
		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		$numrows = $query_data[0];
		return $numrows;
	}

	function getSingle($id){
		$sq = "select * from svc where svc_id = ". prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}

	function update($col_hash, $id){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['svc_id'] = $col_hash['svc_code'];
		$col_hash = prepare_update_params($col_hash);
		$sq = "update svc set " . implode(",",$col_hash) . " " . 
					"where svc_id = " . prepare_param($id);
		return mysql_query($sq) or die(mysql_error());
	}

	function insert($col_hash){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['svc_id'] = $col_hash['svc_code'];
		$col_hash = prepare_params($col_hash);
		$sq = "insert into svc (" . implode(",",array_keys($col_hash)) .") " .
					"values (" . implode(",",$col_hash) . ")";
		mysql_query($sq) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function delete($id){
		$sq = "delete from svc where svc_id = " . prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}


	
}

?>