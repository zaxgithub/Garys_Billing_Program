<?php 
session_start(); 
ob_start();

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");

if(!isset($_POST['page_num'])){$_POST['page_num'] = 1;}//CURRENT PAGE NUMBER; DEFAULT = 1
if(!isset($_POST['page_size'])){$_POST['page_size'] = DEFAULT_ROWS_PER_PAGE;} //NUMBER OF ROWS TO SHOW PER PAGE
if(!isset($_POST['order_by'])){$_POST['order_by'] = "client_id";}
if(!isset($_POST['order_dir'])){$_POST['order_dir'] = "asc";}
if(!isset($_POST['search_term'])){$_POST['search_term'] = "";}
?>
<div id="intQuad">
	<div class="button"><a href="client_modify.php?action=add">CREATE NEW CLIENT</a></div>
	<h2>CLIENT LIST</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner">
<?php include_once(realpath(dirname(__FILE__))."\\includes\\view_search.php"); ?>
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
	$.post("client_view_results.php<?php $q = $_SERVER['QUERY_STRING'];if($q)echo "?".$q;?>", 
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
		</div>
	</div>
</div>
			

<?php require_once(realpath(dirname(__FILE__))."\\includes\\order_form.php"); ?>
<?php require_once(realpath(dirname(__FILE__))."\\includes\\footer.php"); ?>