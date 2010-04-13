<?php 
session_start(); 


error_reporting (E_ALL ^ E_NOTICE);
ini_set('display_errors',0);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\Spreadsheet_Excel_Reader.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\svc.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\tslip.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\client.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\emp.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");

//==================
//SVC SERVICE CLASS
$svc = new svc();
//==================

//==================
//TSLIP CLASS
$tslip = new tslip();
//==================


//==================
//CLIENT CLASS
$Client = new client();
//==================

//==================
//EMP EMPLOYEE CLASS
$emp = new emp();
//==================



//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//FIELD VALUES to add to excel data and populate the database
$exl = array();
$exl['emp_id'] = $_REQUEST['emp_id'];
$exl['emp_name'] = $emp->getNameById($_REQUEST['emp_id']);
$exl['tslip_period_ending'] = $_REQUEST['tslip_period_ending'];
$exl['tslip_status'] = STATUS_PENDING;
$exl['tslip_emp_modified'] = $_SESSION['emp_id'];
$exl['tslip_date_modified'] = CURRENT_MYSQL_TIMESTAMP;
$exl['tslip_date_entered'] = CURRENT_MYSQL_TIMESTAMP;

$file_to_include = SITE_PATH."\\uploads\\".$_REQUEST['filename'];

$template = $_REQUEST['template'];

if($exl['emp_id']=="" || $exl['tslip_period_ending']=="" || $_REQUEST['filename']==""){
	die("ERROR: unknown employeed id or period ending or file does not exist.");
}



if($exl['emp_id']==1){
	$svc_chg_field = "svc_chg_gary";
}elseif($exl['emp_id']==2){
	$svc_chg_field = "svc_chg_glenda";
}elseif($exl['emp_id']==4){
	$svc_chg_field = "svc_chg_jenifer";
}else{
	$svc_chg_field = "svc_chg_default";
}

$emp_default_chrg = $emp->getRateById($exl['emp_id']);
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// SETTINGS : EXCEL READER CLASS
$allow_url_override = 1; // Set to 0 to not allow changed VIA POST or GET
if(!$allow_url_override || !isset($file_to_include))$file_to_include = SITE_PATH."\\uploads\\1-31-09.xls";
if(!$allow_url_override || !isset($max_rows))$max_rows = 0; //USE 0 for no max
if(!$allow_url_override || !isset($max_cols))$max_cols = 25; //USE 0 for no max
if(!$allow_url_override || !isset($debug))$debug = 0;  //1 for on 0 for off
if(!$allow_url_override || !isset($force_nobr))$force_nobr = 1;  //Force the info in cells not to wrap unless stated explicitly (newline)
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


//::::::::::::::::::::::::::::::::::::
//EXCEL READER OBJECT
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CPa25a');
$data->read($file_to_include);
//::::::::::::::::::::::::::::::::::::




// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// VALIDATE IMPORTED TIME SLIP DATA :: FUNCTION

function import_has_errors($import_array){
	// pass client and server objects
	global $Client, $svc;

	$client_id_field = "client_id";
	$svc_id_field = "svc_id";
	$err_array = array();



	if(empty($import_array)){
		$err_array[0][0] = "Nothing to import.. Please try again...";
	}else{
		foreach($import_array As $import_array_sheet => $import_array_row){
		foreach($import_array_row As $k => $v){
			// check if CLIENT_ID EXISTS
			if(!is_numeric($v[$client_id_field])){
				$err_array[$import_array_sheet][$k] = "Bad or Missing Client ID";
			}elseif(!$Client->is_client_id($v[$client_id_field])){
				$err_array[$import_array_sheet][$k] = "Client ID Does Not Exist";
			}
			// check if SVC_ID EXISTS
			if(!is_numeric($v[$svc_id_field])){
				$err_array[$import_array_sheet][$k] = "Bad or Missing Service ID";
			}elseif(!$svc->is_svc_id($v[$svc_id_field])){
				$err_array[$import_array_sheet][$k] = (!empty($err_array[$import_array_sheet][$k])) ? $err_array[$import_array_sheet][$k]."<br />Service Code Does Not Exist" : "Service Code Does Not Exist";
			}

		}
		}
	}
	
				
	//if(!empty($err_array)){
		return $err_array;
	//}else{
	//	return false;
	//}
}



function sum_of_array_value($key,$arr){
	$total = (float)0.0;
	if(!empty($arr) && is_array($arr)){
		foreach($arr As $sheet => $rowz){
			foreach($rowz As $k => $v){
				if(is_numeric($v[$key])){
					$total += (float)$v[$key];
				}
			}
		}
	}
	return $total;
}



// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%





if($template=="gary_time" || $template=="jen_time"){
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//BUILD ARRAY OF USABLE EXCEL CELL DATA

	//.....................
	//ARRAY OF ROW NUMBERS
	$grows = array();
	$grows['start']=8;
	$grows['end']=29;
	$grows['sheets']=20;
	if($template=="jen_time")$grows['sheets']=20;
	//.....................

function garyToArray($rowsets,$data_arry,$svc,$svc_chg_field,$exl){
	global $emp_default_chrg;
	$cell_array = array();
	for($ct=1;$ct<=$rowsets['sheets'];$ct++){
		for($i=$rowsets['start'];$i<=$rowsets['end'];$i++){
			if(!empty($data_arry->sheets[$ct-1]['cells'][$i][2]) && is_numeric($data_arry->sheets[$ct-1]['cells'][$i][2])){
				$cell_array[$ct][$i]['client_id'] = $data_arry->sheets[$ct-1]['cells'][$i][2];
				$cell_array[$ct][$i]['tslip_hours'] = $data_arry->sheets[$ct-1]['cells'][$i][3];
				$cell_array[$ct][$i]['svc_id'] = $data_arry->sheets[$ct-1]['cells'][$i][5];
				$cell_array[$ct][$i]['tslip_remarks'] =$data_arry->sheets[$ct-1]['cells'][$i][6];
				//$svc_amount = 0;//get the default charge for this service
				$amt = trim($data_arry->sheets[$ct-1]['cells'][$i][4]);
				if(!empty($amt) && $amt!=0.00){
					$cell_array[$ct][$i]['tslip_amount'] = $amt;
				}else{
					$svc_amount = $svc->getSvcCharge($data_arry->sheets[$ct-1]['cells'][$i][5],$svc_chg_field);//get the default charge for this service
					if($svc_amount<1 || $svc_amount==0.00) $svc_amount = $emp_default_chrg;
					$cell_array[$ct][$i]['tslip_amount'] = ($svc_amount*$data_arry->sheets[$ct-1]['cells'][$i][3]);
				}
				$cell_array[$ct][$i]['emp_id'] = $exl['emp_id'];
				$cell_array[$ct][$i]['tslip_period_ending'] = $exl['tslip_period_ending'];
				$cell_array[$ct][$i]['tslip_status'] = $exl['tslip_status'];
				$cell_array[$ct][$i]['tslip_emp_modified'] = $exl['tslip_emp_modified'];
				$cell_array[$ct][$i]['tslip_date_modified'] = $exl['tslip_date_modified'];
				$cell_array[$ct][$i]['tslip_date_entered'] = $exl['tslip_date_entered'];
				$cell_array[$ct][$i]['row_num'] = $i;
				$cell_array[$ct][$i]['sheet_num'] = $ct;
			}
		}
	}
	return $cell_array;
}

$cell_array = garyToArray($grows,$data,$svc,$svc_chg_field,$exl);

//if(isset($_REQUEST['debug'])){echo "<hr />\r\n\t<pre>"; var_dump($cell_array);echo "</pre><hr />\r\n\t";}
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
}


















if($template=="glenda_time"){
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//BUILD ARRAY OF USABLE EXCEL CELL DATA

	//.....................
	//ARRAY OF ROW NUMBERS
	$rows = array();
	$rows[0]['start']		=6;
	$rows[0]['end']			=28;
	$rows[1]['start']		=41;
	$rows[1]['end']			=64;
	$rows[2]['start']		=78;
	$rows[2]['end']			=101;
	$rows[3]['start']		=113;
	$rows[3]['end']			=138;
	$rows[4]['start']		=148;
	$rows[4]['end']			=173;
	$rows[5]['start']		=183;
	$rows[5]['end']			=208;

	$rows[6]['start']		=218;
	$rows[6]['end']			=243;
	$rows[7]['start']		=253;
	$rows[7]['end']			=278;
	$rows[8]['start']		=288;
	$rows[8]['end']			=313;
	$rows[9]['start']		=323;
	$rows[9]['end']			=348;
	$rows[10]['start']	=358;
	$rows[10]['end']		=383;
	$rows[11]['start']	=393;
	$rows[11]['end']		=418;
	$rows[12]['start']	=428;
	$rows[12]['end']		=453;
	$rows[13]['start']	=463;
	$rows[13]['end']		=488;
	$rows[14]['start']	=498;
	$rows[14]['end']		=523;
	$rows[15]['start']	=533;
	$rows[15]['end']		=558;
	$rows[16]['start']	=568;
	$rows[16]['end']		=593;
	$rows[17]['start']	=603;
	$rows[17]['end']		=628;
	$rows[18]['start']	=638;
	$rows[18]['end']		=663;
	$rows[19]['start']	=673;
	$rows[19]['end']		=698;
	$rows[20]['start']	=708;
	$rows[20]['end']		=733;
	//.....................

function cellsToArray($rowsets,$data_arry,$svc,$svc_chg_field,$exl){
	global $emp_default_chrg;
	$cell_array = array();
	for($ct=0;$ct<=sizeof($rowsets);$ct++){
		for($i=$rowsets[$ct]['start'];$i<=$rowsets[$ct]['end'];$i++){
			if	(
				(!is_nan($data_arry->sheets[0]['cells'][$i][18]) && 
				trim($data_arry->sheets[0]['cells'][$i][18])!="") || 
				$data_arry->sheets[0]['cells'][$i][21]>0 
				){
				$cell_array[0][$i]['client_id'] = $data_arry->sheets[0]['cells'][$i][18];
				$cell_array[0][$i]['tslip_hours'] = $data_arry->sheets[0]['cells'][$i][19];
				$cell_array[0][$i]['svc_id'] = $data_arry->sheets[0]['cells'][$i][21];
				$cell_array[0][$i]['tslip_remarks'] =$data_arry->sheets[0]['cells'][$i][22];
				$svc_amount = $svc->getSvcCharge($data_arry->sheets[0]['cells'][$i][21],$svc_chg_field);//get the default charge for this service
				if($svc_amount<1 || $svc_amount==0.00) $svc_amount = $emp_default_chrg;
				$cell_array[0][$i]['tslip_amount'] = ($svc_amount*$data_arry->sheets[0]['cells'][$i][19]);
				$cell_array[0][$i]['emp_id'] = $exl['emp_id'];
				$cell_array[0][$i]['tslip_period_ending'] = $exl['tslip_period_ending'];
				$cell_array[0][$i]['tslip_status'] = $exl['tslip_status'];
				$cell_array[0][$i]['tslip_emp_modified'] = $exl['tslip_emp_modified'];
				$cell_array[0][$i]['tslip_date_modified'] = $exl['tslip_date_modified'];
				$cell_array[0][$i]['tslip_date_entered'] = $exl['tslip_date_entered'];
				$cell_array[0][$i]['row_num'] = $i;
				$cell_array[0][$i]['sheet_num'] = 1;
			}
		}
	}
	return $cell_array;
}

$cell_array = cellsToArray($rows,$data,$svc,$svc_chg_field,$exl);

//if(isset($_REQUEST['debug'])){echo "<hr />\r\n\t"; print_r($cell_array);echo "<hr />\r\n\t";}
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
}








//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
//DUMP EXCEL DATA 
if(isset($_REQUEST['debug'])){
	
echo "<hr /><pre>\r\n\t"; print_r($cell_array);echo "</pre><hr />\r\n\t";
/*for($i=0;$i<$grows['sheets'];$i++){
foreach($data->sheets[$i] as $k => $v){
	echo $k.": ";
	foreach($v as $key => $val){
		echo "<br /> ->".$key.": "."\r\n";
		foreach($val as $keyz => $valz){
			echo "<br />  ->->".$keyz.": ".$valz."\r\n";
		}
	}
	echo "<br />\r\n";
}
}*/

}
//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
?>

<div id="intQuad">
	<h2>HOME</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner" align="center">
				<div align="center" style="clear:both;float:none;height:auto !important;vertical-align:middle;text-align:center !important;border:0;" align="center">

<?php
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// ******************************
// CHECK FOR ERRORS IN THE IMPORT

$import_errors = import_has_errors($cell_array);
//echo "<hr /><pre>\r\n\t"; print_r($import_errors);echo "</pre><hr />\r\n\t";

$total_errors = 0;

foreach($import_errors As $err_row){
	$tmp_arr = $err_row;
	$total_errors += count($tmp_arr);
}
$total_rows = 0;

foreach($cell_array As $row_err_row){
	$row_tmp_arr = $row_err_row;
	$total_rows += count($row_tmp_arr);
}

$total_valid = $total_rows - $total_errors;

if($total_errors>0){
	$total_color_light = "#FFEFF0";
	$total_color_dark = "#FF000A";
	$total_msg = "
					<div style='height:100px;text-align:center;padding:5px;margin:5px;border:dotted 2px $total_color_dark'>
						<em>*** IMPORT ABORTED! ***</em>
						<br />
						Please fix the errors in the report below and try the upload again.
						<div align='center' style='clear:both;padding:3px 5px 3px 3px;margin:13px 5px 3px 3px;height:block;'>
							<div class=\"button\"><a href=\"client_modify.php?action=add\" style=\"background-color:green;color:white;font-size:11px;font-weight:bold;padding:2px 5px;\">ADD NEW CLIENT</a></div>
							<div class=\"button\"><a href=\"svc_modify.php?action=add\" style=\"background-color:green;color:white;font-size:11px;font-weight:bold;padding:2px 5px;\">ADD NEW SERVICE</a></div>
							<div class=\"button\"><a href=\"import.php\" style=\"background-color:green;color:white;font-size:11px;font-weight:bold;padding:2px 5px;\">TRY AGAIN</a></div>
						</div>
					</div>
				";
}else{
	$total_color_light = "#EFFFEF";
	$total_color_dark = "green";
	$total_msg = "
					<div style='height:100px;text-align:center;padding:5px;margin:5px;border:dotted 2px $total_color_dark'>
						<em>*** IMPORT COMPLETED! ***</em>
						<br />
						All of your time slips have been imported successfully. You may now continue.
						<div align='center' style='clear:both;padding:3px 22px 3px 3px;margin:13px 22px 3px 3px;height:block;'>
							<div class=\"button\"><a href=\"tslip_modify.php?action=add\" style=\"background-color:green;color:white;font-size:11px;font-weight:bold;padding:2px 5px;\"> CONTINUE >></a></div>
							<div class=\"button\"><a href=\"import.php\" style=\"background-color:green;color:white;font-size:11px;font-weight:bold;padding:2px 5px;\"><< IMPORT ANOTHER FILE </a></div>
						</div>
					</div>
				";

}
$total_summary_html = "<div align='center'>
						<div style='font-size:16px;width:350px;border:solid 3px $total_color_dark;background-color:$total_color_light;padding:20px;margin:30px;'>
							Errors = $total_errors<br />
							Valids = $total_valid<br />
							<hr />
							Total Records = $total_rows<br />
							$total_msg
						</div>
					</div>
					";


//var_dump($import_errors);
//********************************************************************
//BUILD TABLE FROM ARRAY
$header_style = 'padding:5px 10px;background-color:#efefef;font-weight:bold;font-size:14px;color:#666;border-bottom:double 3px #000;border-right:dotted 1px #eee;';


echo $total_summary_html;
echo "<div align='center'>\r\n";
echo "<table cellpadding='0' cellspacing='0' border='0' style='border:solid 1px #999;clear:both;margin-bottom:25px;' align='center'>\r\n";
		echo "<tr>\r\n";
			echo "<td style='".$header_style."'>Sheet#</td>\r\n";
			echo "<td style='".$header_style."'>Row#</td>\r\n";
			echo "<td style='".$header_style."'>Client</td>\r\n";
			echo "<td style='".$header_style."'>Hours</td>\r\n";
			echo "<td style='".$header_style."'>Service</td>\r\n";
			echo "<td style='".$header_style."'>Remarks</td>\r\n";
			echo "<td style='".$header_style."'>Amount</td>\r\n";
			echo "<td style='".$header_style."'>Emp</td>\r\n";
			echo "<td style='".$header_style."'>Period Ending</td>\r\n";
			echo "<td style='".$header_style."'>Errors</td>\r\n";
		echo "</tr>\r\n";		


foreach($cell_array As $cell_array_sheet => $cell_array_row){
	foreach($cell_array_row as $k => $v){
			if(empty($import_errors[$cell_array_sheet][$k])){
				$error_style = "color:green !important;border-bottom:solid 1px #ccc;border-right:dotted 1px #ccc;padding:5px 10px;";
			}else{
				$error_style = "color:red;padding:5px 10px;border-bottom:solid 1px #ccc;border-right:dotted 1px #ccc;";
			}
			

			
			echo "<tr>\r\n";
				echo "<td align='left' style='".$error_style."'>".$v['sheet_num']."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".$v['row_num']."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".$v['client_id']." : ".$Client->getClientName($v['client_id'])."</td>\r\n";
				echo "<td align='right' style='".$error_style."'>".number_format($v['tslip_hours'],2)."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".$v['svc_id']." : ".$svc->getSvcName($v['svc_id'])."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".$v['tslip_remarks']." &nbsp;</td>\r\n";
				echo "<td align='right' style='".$error_style."'>".number_format($v['tslip_amount'],2)."</td>\r\n";
				//echo "<td align='left' style='".$error_style."'>".$v['emp_id']." : ".$exl['emp_name']."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".$exl['emp_name']."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".format_date($v['tslip_period_ending'], 'small')."</td>\r\n";
				echo "<td align='left' style='".$error_style."'>".((!empty($import_errors[$cell_array_sheet][$k])) ? $import_errors[$cell_array_sheet][$k] : "OK") ."</td>\r\n";
				echo "</tr>\r\n";
	}
}
$total_style = 'padding:7px 10px;background-color:#666;font-weight:bold;color:#fff;border-top:solid 3px #000;border-right:0;';
		echo "<tr>\r\n";
			echo "<td style='".$total_style."'>&nbsp;</td>\r\n";
			echo "<td style='".$total_style."'>&nbsp;</td>\r\n";
			echo "<td align='right' style='".$total_style."'>TOTAL HOURS</td>\r\n";
			echo "<td align='right' style='".$total_style."'>= ".sum_of_array_value('tslip_hours',$cell_array)."</td>\r\n";
			echo "<td style='".$total_style."'>&nbsp;</td>\r\n";
			echo "<td align='right' style='".$total_style."'>TOTAL AMOUNT</td>\r\n";
			echo "<td align='right' style='".$total_style."'>= ".makeMoney(sum_of_array_value('tslip_amount',$cell_array))."</td>\r\n";
			echo "<td style='".$total_style."'>&nbsp;</td>\r\n";
			echo "<td style='".$total_style."'>&nbsp;</td>\r\n";
			echo "<td style='".$total_style."'>&nbsp;</td>\r\n";
			echo "</tr>\r\n";

echo "</table>\r\n";
echo "</div>\r\n";
//********************************************************************

			//$insert_array = delArrayElementByKey($cell_array[$sheet][$k],'row_num');
			//$insert_array = delArrayElementByKey($insert_array[$sheet][$k],'sheet_num');
			// if no errors exist in the import then insert the row
			//if(!$import_errors)$tslip->insert($insert_array);

			// if no errors exist in the import then insert the row
			if(!$import_errors){
				foreach($cell_array As $sheet => $sheet_rows){
					foreach($sheet_rows As $rownum => $rowval){
						$insert_array = delArrayElementByKey($rowval,'row_num');
						$insert_array = delArrayElementByKey($insert_array,'sheet_num');
						$tslip->insert($insert_array);
					}
				}
			}
?>

				</div>
		</div>
	</div>
</div>
			
			
<?php 
require_once(realpath(dirname(__FILE__))."\\includes\\footer.php");
?>