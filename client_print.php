<?php
error_reporting (E_ALL ^ E_WARNING);


require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");


if(!isset($_POST['page_num'])){$_POST['page_num'] = 1;}//CURRENT PAGE NUMBER; DEFAULT = 1
if(!isset($_POST['page_size'])){$_POST['page_size'] = DEFAULT_ROWS_PER_PAGE;} //NUMBER OF ROWS TO SHOW PER PAGE
if(!isset($_POST['order_by'])){$_POST['order_by'] = "client_id";}
if(!isset($_POST['order_dir'])){$_POST['order_dir'] = "asc";}
if(!isset($_POST['search_term'])){$_POST['search_term'] = "";}
?>
<?php require_once(SITE_PATH."\\functions\\utility.functions.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo DEFAULT_TITLE; ?></title>



<?php include_once(SITE_PATH."\\includes\\base.js.php"); ?>

</head>
<body>
	
<div id="intQuad">
	<h2>CLIENT LIST</h2><h5> <?php echo date('l jS \of F Y h:i:s A'); ?></h5><br />
	<div id="intQuadOuter">
		<div id="intQuadInner">
				<div id="view_content_print" class="view_content_print">
<?php 
/***************************************************************************************************************/
error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\client.class.php");


$db = new client();
if($_GET['action']=="delete" && isset($_GET['id'])){	$result = $db->delete($_GET['id']);}



// ############ PAGINATION ############
$_POST['row_total'] = $db->getAllTotal($_POST['search_term']);
$_POST['page_last'] = ""; //CALCULATE LAST PAGE NUMBER
$_POST['page_num'] = "";//DONT GO LESS THAN PAGE 1
$_POST['row_limit'] = "";
$_POST['order_dir'] = "";
$_POST['search_term'] = "";
$_POST['order_by'] = "";
$_POST['page_size']="";
$oby = (!empty($_GET["client_id"]))?$_GET["client_id"] : "client_id";
$odir= "asc";
// ############ SIMPLE SEARCH ########### //
$db_rows = $db->getPrintResults($oby, $odir);
?>
<div align="center">

	<h4><?php echo $_POST['row_total']; ?> Total Clients</h4>
<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:20px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_id','<?php echo switchDir($_POST['order_dir']); ?>');">CLIENT #</td>
		<td class="ordertable_head"><a href="javascript:void(0);" onclick="orderTable(1,<?php echo $_POST['page_size']; ?>,'client_name','<?php echo switchDir($_POST['order_dir']); ?>');">NAME</a></td>
	</tr>
<?php

	if (mysql_num_rows($db_rows) > 0) {
		while($db_row = mysql_fetch_assoc($db_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\" style=\"border-bottom:solid 1px #000;font-size:14pt;text-align:right;padding:2px 15px;\">".$db_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\" style=\"border-bottom:solid 1px #000;font-size:14pt;text-align:left;padding:2px 15px;\">".$db_row['client_name']."</td>\r\n";
			echo "</tr>\r\n";
		}
	}else{
		echo "<tr><td align=\"center\" colspan=\"5\">\r\n";
		echo "There are no results!\r\n";
		echo "</td></tr>\r\n";
	}

?>
</table>
</div>
<?php //echo $pg_prev.$pg_next.$pg_info;
/***************************************************************************************************************/
/***************************************************************************************************************/
 ?>












				</div>
		</div>
	</div>
</div>
<script type="text/javascript">
window.print();
</script>

</body>
</html>