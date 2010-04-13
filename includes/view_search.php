<?php
if(!isset($view_search_options)){
$view_search_options = array();
$view_search_options['date'] = false;
$view_search_options['status'] = false;
$view_search_options['filter'] = "simple_search_text";
$view_search_options['employee'] = false;
$view_search_options['client'] = false;
}





?>
<div id="view_search_container" align="center">
	<form name="view-search-form" id="view-search-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin:0px;padding:0px;">

<?php
// ###################################################################################
// [CLIENT] #################################
if($view_search_options['client']){
?>
<div id="view_search_item" style="vertical-align:middle;">
	CLIENT:
	<div id="search_client_id"></div>
</div>
<script type="text/javascript">
var cid = $('#search_client_id').flexbox("client_lookup.php", {
allowInput: true,
autoCompleteFirstMatch: true,
arrowQuery: "null",
paging: false,
maxVisibleRows: 5
});
cid.setValue('<?php echo (isset($_COOKIE['client_id'])? $_COOKIE['client_id'] : $_REQUEST['client_id']); ?>');
</script>



<?php
}
// [/CLIENT] ###############################
// ##################################################################################
?>


	
<?php
// ###################################################################################
// [FILTER] #################################
if($view_search_options['filter']){
?>
<div id="view_search_item">
	FILTER:<br />
	<input type="text" name="<?php echo $view_search_options['filter']; ?>" id="<?php echo $view_search_options['filter']; ?>" autocomplete="off" value="<?php echo $_POST['search_term']; ?>" onFocus="$('#search_container').css('background','#fffeee');" onBlur="$('#search_container').css('background','transparent');"/> 
</div>
<script type="text/javascript" language="Javascript">
	$("#<?php echo $view_search_options['filter']; ?>").keyup(function() {
		$("#search_term").val($("#<?php echo $view_search_options['filter']; ?>").val());
		$.post("<?php echo str_replace('.php','_results.php',script_name()); ?><?php $q = $_SERVER['QUERY_STRING'];if($q)echo "?".$q;?>", 
					{	search_term: $("#simple_search_text").val(),
						page_num: $("#page_num").val(),
						page_size: $("#page_size").val(),
						order_by: $("#order_by").val(),
						order_dir: $("#order_dir").val()}, 
		  function(data){
		    $('#view_content').html(data);
		  },"html");
	});
	
</script>


<?php
}
// [/FILTER] ###############################
// ##################################################################################
?>


<?php
// #################################################################################
// [DATE] #################################
if($view_search_options['date']){
	if(isset($_REQUEST[$view_search_options['date']])) $_SESSION["period_ending"]=$_REQUEST['billing_date'];
?>
<div id="view_search_item">
DATE:<br />
<input type="text" style="float:none;" name="<?php echo $view_search_options['date']; ?>" id="<?php echo $view_search_options['date']; ?>" autocomplete="off" value="<?php echo $_REQUEST[$view_search_options['date']]; ?>" onFocus="$('#search_container').css('background','#fffeee');" onBlur="$('#search_container').css('background','transparent');" />
</div>
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
$("#<?php echo $view_search_options['date']; ?>").datepicker({
		defaultDate: new Date($(this).val()),
    dateFormat: $.datepicker.ISO_8601, 
    showOn: "button", 
    buttonImage:  "images/calendar_popup.gif", 
    buttonImageOnly: true 
});
});
</script>
<?php
}
// [/DATE] ###############################
// #################################################################################
?>

<?php
// ###################################################################################
// [STATUS] #################################
if($view_search_options['status']){
?>
<div id="view_search_item">
 &nbsp; &nbsp; &nbsp; 
STATUS:<br />
<?php
echo $form->getStatusSelect($view_search_options['status'],$_REQUEST[$view_search_options['status']]);
?>
</div>
<?php
}
// [/STATUS] ###############################
// ##################################################################################




if(!$view_search_options['filter']){
	echo "<input type=\"submit\" name=\"\" id=\"\" value=\"search >>\" />";
}
?>






<script type="text/javascript">
<!--//


$(document).ready(function() {
<?php
if($view_search_options['client']){
		echo "$('#".$view_search_options['client']."_input').focus();";
	}elseif($view_search_options['employee']){
		echo "$('#".$view_search_options['employee']."').focus();";
	}elseif($view_search_options['date']){
		echo "$('#".$view_search_options['date']."').focus();";
	}elseif($view_search_options['filter']){
		echo "$('#".$view_search_options['filter']."').focus();";
	}elseif($view_search_options['status']){
		echo "$('#".$view_search_options['status']."').focus();";
	}
?>

});
//-->
</script>
</form>
</div>