<?php
require_once(SITE_PATH."\\functions\\utility.functions.php");

class billing
{
	function billing(){}



// ########### BILLING
	function getAll($start=0, $end=10, $orderby="billing_id", $dir="asc"){

$sq = "	(SELECT 
				b.client_id,
				b.bslip_id as billing_id,
				b.bslip_date_worked as billing_date,				
				null as billing_hours,
				b.bslip_amount as billing_amount,
				b.bslip_remarks as billing_remarks,
				CASE 
					b.bslip_status
					WHEN
						".STATUS_ACTIVE." THEN 'Active'
					WHEN
						".STATUS_INACTIVE." THEN 'Inactive'
					WHEN
						".STATUS_PENDING." THEN 'Pending'
					WHEN
						".STATUS_CLEARED." THEN 'Cleared'
					WHEN
						".STATUS_DELETED." THEN 'Deleted'
				END as billing_status,
				e.emp_nickname as billing_employee,
				c.client_name as billing_client,
				'bill' as billing_type
				FROM
				bslip b
                INNER JOIN
                emp e on b.emp_id = e.emp_id
								INNER JOIN
								client c on b.client_id = c.client_id
								)
				UNION ALL
				(SELECT
				t.client_id,
				t.tslip_id as billing_id,
				t.tslip_period_ending as billing_date,
				t.tslip_hours as billing_hours,
				t.tslip_amount as billing_amount,
				t.tslip_remarks as billing_remarks,
				CASE 
					t.tslip_status
					WHEN
						".STATUS_ACTIVE." THEN 'Active'
					WHEN
						".STATUS_INACTIVE." THEN 'Inactive'
					WHEN
						".STATUS_PENDING." THEN 'Pending'
					WHEN
						".STATUS_CLEARED." THEN 'Cleared'
					WHEN
						".STATUS_DELETED." THEN 'Deleted'
				END as billing_status,
				e.emp_nickname as billing_employee,
				c.client_name as billing_client,
				'time' as billing_type
				FROM
				tslip t
                INNER JOIN
                emp e on t.emp_id = e.emp_id
								INNER JOIN
								client c on t.client_id = c.client_id
								)
				order by ".$orderby." ".$dir.
				" limit ".$start.", ".$end;

		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
// ###################














// ########### BILLING
	function getAllBySearch($key_val=''){
		$b_whr = "";
		$t_whr = "";
		
		if(empty($key_val)){
			$key_val=array("fake_one"=>"true");
		}

		// CLIENT ID
		if(isset($key_val['search_client_id']) && $key_val['search_client_id']!=""){
			$b_whr.=" AND b.client_id = ".$key_val['search_client_id'];
			$t_whr.=" AND t.client_id = ".$key_val['search_client_id'];
		}
		// DATE OF WORK
		if(isset($key_val['billing_date_search']) && $key_val['billing_date_search']!=""){
			$b_whr.=" AND (b.bslip_date_worked BETWEEN '".$key_val['billing_date_search']."' and '".getDatePeriod($key_val['billing_date_search'])."' OR b.bslip_date_worked = '".$key_val['billing_date_search']."')";
			$t_whr.=" AND t.tslip_period_ending = '".getDatePeriod($key_val['billing_date_search'])."'";
		}
		// STATUS
		if(isset($key_val['billing_status']) && $key_val['billing_status']!=""){
			$b_whr.=" AND b.bslip_status = ".$key_val['billing_status'];
			$t_whr.=" AND t.tslip_status = ".$key_val['billing_status'];
		}elseif($key_val['billing_status']==""){
			$b_whr.=" AND b.bslip_status = ".STATUS_ACTIVE;
			$t_whr.=" AND t.tslip_status  = ".STATUS_ACTIVE;
		}else{
			$b_whr.=" AND b.bslip_status = 1";
			$t_whr.=" AND t.tslip_status  = 1";
		}


$sq = "	(SELECT 
				b.client_id,
				b.bslip_id as billing_id,
				b.bslip_date_worked as billing_date,				
				null as billing_hours,
				b.bslip_amount as billing_amount,
				b.bslip_remarks as billing_remarks,
				CASE 
					b.bslip_status
					WHEN
						".STATUS_ACTIVE." THEN 'Active'
					WHEN
						".STATUS_INACTIVE." THEN 'Inactive'
					WHEN
						".STATUS_PENDING." THEN 'Pending'
					WHEN
						".STATUS_CLEARED." THEN 'Cleared'
					WHEN
						".STATUS_DELETED." THEN 'Deleted'
				END as billing_status,
				e.emp_nickname as billing_employee,
				c.client_name as billing_client,
				'bill' as billing_type
				FROM
				bslip b
                INNER JOIN
                emp e on b.emp_id = e.emp_id
								INNER JOIN
								client c on b.client_id = c.client_id 
								WHERE
								b.client_id > 0
								".$b_whr." 
								)
				UNION ALL
				(SELECT
				t.client_id,
				t.tslip_id as billing_id,
				t.tslip_period_ending as billing_date,
				t.tslip_hours as billing_hours,
				t.tslip_amount as billing_amount,
				t.tslip_remarks as billing_remarks,
				CASE 
					t.tslip_status
					WHEN
						".STATUS_ACTIVE." THEN 'Active'
					WHEN
						".STATUS_INACTIVE." THEN 'Inactive'
					WHEN
						".STATUS_PENDING." THEN 'Pending'
					WHEN
						".STATUS_CLEARED." THEN 'Cleared'
					WHEN
						".STATUS_DELETED." THEN 'Deleted'
				END as billing_status,
				e.emp_nickname as billing_employee,
				c.client_name as billing_client,
				'time' as billing_type
				FROM
				tslip t
                INNER JOIN
                emp e on t.emp_id = e.emp_id
								INNER JOIN
								client c on t.client_id = c.client_id
								where 
								t.client_id > 0
								".$t_whr." 
				)
				 ORDER BY client_id, billing_date, billing_employee
				";

		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
// ###################

























// ########### BILLING
	function getAllById($start=0, $end=10, $orderby="billing_id", $dir="asc", $search_term=""){

$sq = "	(SELECT
				b.client_id,
				b.bslip_id as billing_id,
				b.bslip_date_worked as billing_date,				
				null as billing_hours,
				b.bslip_amount as billing_amount,
				b.bslip_remarks as billing_remarks,
				CASE 
					b.bslip_status
					WHEN
						".STATUS_ACTIVE." THEN 'Active'
					WHEN
						".STATUS_INACTIVE." THEN 'Inactive'
					WHEN
						".STATUS_PENDING." THEN 'Pending'
					WHEN
						".STATUS_CLEARED." THEN 'Cleared'
					WHEN
						".STATUS_DELETED." THEN 'Deleted'
				END as billing_status,
				e.emp_nickname as billing_employee,
				c.client_name as billing_client,
				'bill' as billing_type
				FROM
				bslip b
                INNER JOIN
                emp e on b.emp_id = e.emp_id
								INNER JOIN
								client c on b.client_id = c.client_id
				WHERE
				b.client_id = ".$search_term.")
				UNION ALL
				(SELECT
				t.client_id,
				t.tslip_id as billing_id,
				t.tslip_period_ending as billing_date,
				t.tslip_hours as billing_hours,
				t.tslip_amount as billing_amount,
				t.tslip_remarks as billing_remarks,
				CASE 
					t.tslip_status
					WHEN
						".STATUS_ACTIVE." THEN 'Active'
					WHEN
						".STATUS_INACTIVE." THEN 'Inactive'
					WHEN
						".STATUS_PENDING." THEN 'Pending'
					WHEN
						".STATUS_CLEARED." THEN 'Cleared'
					WHEN
						".STATUS_DELETED." THEN 'Deleted'
				END as billing_status,
				e.emp_nickname as billing_employee,
				c.client_name as billing_client,
				'time' as billing_type
				FROM 
				tslip t
                INNER JOIN
                emp e on t.emp_id = e.emp_id
								INNER JOIN
								client c on t.client_id = c.client_id
				WHERE
				t.client_id = ".$search_term.")
				order by ".$orderby." ".$dir.
				" limit ".$start.", ".$end;

		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
// ###################
                                            
	function getAllTotal($search_term=""){
$sq = "SELECT COUNT(*) FROM bslip,tslip";

		$result = mysql_query($sq) or die(mysql_error());
		$query_data = mysql_fetch_row($result);
		$numrows = $query_data[0]+$query_data[1];
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


	function update_all_in($col_hash, $ids, $tbl="tslip"){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash[$tbl.'_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash[$tbl.'_emp_modified'] = $_SESSION['emp_id'];
		$col_hash = prepare_update_params($col_hash);
		$sq = "update ".$tbl." set " . implode(",",$col_hash) . " " . 
					"where ".$tbl."_id IN(" . $ids . ")";
		return mysql_query($sq) or die(mysql_error());
	}





	function insert($col_hash){
		if (sizeof($col_hash) == 0)
			return false;
		$col_hash['bslip_date_entered'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['bslip_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
		$col_hash['bslip_emp_modified'] = $_SESSION['emp_id'];
		$col_hash = prepare_params($col_hash);
		$sq = "insert into bslip (" . implode(",",array_keys($col_hash)) .") " .
					"values (" . implode(",",$col_hash) . ")";
		mysql_query($sq) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function delete($id,$table){
		$sq = "delete from ".$table." where ".$table."_id = " . prepare_param($id);
		$result = mysql_query($sq) or die(mysql_error());
		return $result;
	}
	
	




}
?>