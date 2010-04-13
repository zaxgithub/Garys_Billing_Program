<?php 
session_start(); 
ob_start();

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\functions\\utility.functions.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\classes\\emp.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\form.class.php");
require_once(realpath(dirname(__FILE__))."\\classes\\tslip.class.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");

$emp = new emp();
$db = new tslip();
$form = new form();

if($_REQUEST['action']=="savepending" && is_array($_POST['tslip_pending_selected'])){	
	$result = $db->save_slips_in(implode(",",$_POST['tslip_pending_selected']));
}
if($_REQUEST['action']=="deletepending" && is_array($_POST['tslip_pending_selected'])){	
	$result = $db->delete_slips_in(implode(",",$_POST['tslip_pending_selected']));
}



// GRAB PENDING SLIPS FOR THIS EMPLOYEE WHO IS LOGGED IN
$p_rows = $db->getAllPending($_SESSION['emp_id']);


if(!isset($_POST['page_num'])){$_POST['page_num'] = 1;}//CURRENT PAGE NUMBER; DEFAULT = 1
if(!isset($_POST['page_size'])){$_POST['page_size'] = DEFAULT_ROWS_PER_PAGE;} //NUMBER OF ROWS TO SHOW PER PAGE
if(!isset($_POST['order_by'])){$_POST['order_by'] = "tslip_id";}
if(!isset($_POST['order_dir'])){$_POST['order_dir'] = "asc";}
if(!isset($_POST['search_term'])){$_POST['search_term'] = "";}






?>
<script type="text/javascript">
	function toggleHeight(EL,MIN_VAL,MAX_VAL){
		var toggle_el_id = "toggle_switch";
		var toggle_msg_expand = "[ + ] EXPAND VIEW";
		var toggle_msg_colapse = "[ - ] COLAPSE VIEW";
		
		if(document.getElementById(EL).style.height==MIN_VAL){
			document.getElementById(EL).style.height=MAX_VAL;
			document.getElementById(toggle_el_id).innerHTML=toggle_msg_colapse;
		}else{
			document.getElementById(EL).style.height=MIN_VAL;
			document.getElementById(toggle_el_id).innerHTML=toggle_msg_expand;
		}
	}



	
	
	

</script>
<div id="intQuad">
	<div class="button"><a href="tslip_modify.php?action=add">CREATE NEW TIME SLIP</a></div>
	<h2>TIME SLIPS</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner">
<?php include_once(realpath(dirname(__FILE__))."\\includes\\view_search.php"); ?>
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
	$.post("tslip_view_results.php<?php $q = $_SERVER['QUERY_STRING'];if($q)echo "?".$q;?>", 
				{	search_term: $("#simple_search_text").val(),
					page_num: $("#page_num").val(),
					page_size: $("#page_size").val(),
					order_by: $("#order_by").val(),
					order_dir: $("#order_dir").val()},
			  function(data){
			    $('#view_content').html(data);
			  },
			  "html"
			  );

});

</script>







				<div id="view_content" class="view_content">



				</div>
				
<form action="<?php echo script_name(); ?>" method="post" name="form_tslip_pending" id="form_tslip_pending">

<div style="margin:20px 10px 10px 10px;padding:0px 10px;border:solid 1px #ccc;background:#f9f9f9;width:auto;clear:both;float:none;" align="center">
<!-- ********************************************************************************************* -->
<!-- ************************* [START] PENDING SLIPS WINDOW ************************************** -->
<?php 	if (mysql_num_rows($p_rows) > 0) {?>
<div align="center" style="color:green;"><?php echo $message; ?></div>
<br class="clearit" />
<?php echo strtoupper($_SESSION['emp_nickname']); ?>'s PENDING TIME SLIPS: <a href="javascript:void(0);" id="toggle_switch" onClick="toggleHeight('pending_view','100px','auto')" style="margin-left:600px;text-decoration:none;">[ + ] EXPAND VIEW</a>



<div id="pending_view" style="margin:10px 0px 0px 0px;border:solid 1px #ccc;background:#FEEFFF;width:auto;height:100px;overflow:auto;clear:both;float:none;" align="center">


<table id="ordertablemain" cellpadding="0" cellspacing="0" align="center" style="margin:5px;border-top:solid 1px #999;">
	<tr>
		<td class="ordertable_head"><?php echo makeCheckBox('checkboxall_pending','',''); ?></td>
		<td class="ordertable_head">EMPLOYEE</td>
		<td class="ordertable_head">CLIENT</td>
		<td class="ordertable_head">HOURS</td>
		<td class="ordertable_head">AMOUNT</td>
		<td class="ordertable_head">PERIOD END</td>
		<td class="ordertable_head">REMARKS</td>
		<td class="ordertable_head">ACTION</td>
	</tr>
<?php
	$total_hours = 0;
	$total_amount = 0;

		while($p_row = mysql_fetch_assoc($p_rows)){
			echo "<tr class=\"ordertable_row\">\r\n";
			echo "<td class=\"ordertable_orderno\">".makeCheckBox("tslip_pending_selected[]",$p_row["tslip_id"],"")."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['emp_nickname']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['client_id']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".$p_row['tslip_hours']."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".makeMoney($p_row['tslip_amount'])."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".format_date($p_row['tslip_period_ending'],'small')."</td>\r\n";
			echo "<td class=\"ordertable_orderno\">".shortString($p_row['tslip_remarks'])."</td>\r\n";
			echo "<td class=\"ordertable_action\"><a href=\"tslip_modify.php?id=".$p_row['tslip_id']."&action=edit\" id=\"link\">EDIT</a><a href=\"javascript:void(0);\" onClick=\"deleteRecord(".$p_row['tslip_id'].");\" id=\"link\">DELETE</a></td>\r\n";
			echo "</tr>\r\n";
			$total_hours += $p_row['tslip_hours'];
			$total_amount += $p_row['tslip_amount'];
		}

?>
</table>
</div>

Total Hours: <?php echo $total_hours; ?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
Total Amount: <?php echo makeMoney($total_amount); ?>
<br />

<br class="clearit" />
<input type="button" onclick="javascript:confirmSavePending();" value=" SAVE SELECTED " style="cursor:pointer;background-color:green;color:#fff;font-weight:bold;padding:3px;border:solid 2px black;" /> &nbsp; &nbsp; 
<input type="button" onclick="javascript:confirmDeletePending();" value=" DELETE SELECTED " style="cursor:pointer;background-color:red;color:#fff;font-weight:bold;padding:3px;border:solid 2px pink;" />
<input type="hidden" name="action" id="form_tslip_pending_action" value="" />

<div style="height:1px;width:90%;margin:10px;border-top:dotted 1px #ccc;clear:both;"></div>
<?php 	} ?>
<!-- ************************* [END] PENDING SLIPS WINDOW ************************************** -->
<!-- ********************************************************************************************* -->
</div>
</form>
				
				
		</div>
	</div>
</div>
			
<?php
echo "<pre>";
var_dump($_POST);
echo "</pre>";
?>			

<?php require_once(realpath(dirname(__FILE__))."\\includes\\order_form.php"); ?>
<?php require_once(realpath(dirname(__FILE__))."\\includes\\footer.php"); ?>