<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jqueryui.js"></script>

<link rel="stylesheet" type="text/css" href="css/base.css" />
<link rel="stylesheet" type="text/css" href="css/jqueryui.css" />


<!-- ################ -->
<!-- TOOLTIPS: JQUERY -->
		<script type="text/javascript" language="Javascript" src="js/jquery.tooltip.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" language="Javascript">
            $(document).ready(function(){
                $("a").tooltip({
                    track: true,
                    delay: 0,
                    showURL: false,
                    showBody: " - ",
                    fixPNG: true,
                    extraClass: "tiny_tooltip",
                    opacity: 0.90
                });
            });
        </script>
<!-- ################ -->


<script type="text/javascript" src="js/jquery.flexbox.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.flexbox.css" />

<script type="text/javascript" src="js/jquery.thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.thickbox.css" />




<script type="text/javascript" src="js/clock.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>



<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
<!-- /TinyMCE -->


<script type="text/javascript" language="Javascript">
	<!--//
	function deleteRecord(id){
		if(confirm('Are you sure you want to permanently delete this record #' + id)){
			window.location.href = "<?php echo script_name(); ?>?action=delete&id=" + id;
		}
	}

	function orderTable(PGNUM, PGSIZE, OBY, ODIR){
		document.order_form.page_num.value = PGNUM;
		document.order_form.page_size.value = PGSIZE;
		document.order_form.order_by.value = OBY;
		document.order_form.order_dir.value = ODIR;
		//alert('orderTable: '+document.order_form.search_term.value);
		document.order_form.submit();
	}
	
	function filterTable(){
		document.order_form.search_term.value = document.simple_search_form.simple_search_text.value;
		//alert('filterTable: '+document.order_form.search_term.value);
		document.order_form.submit();
	}
	//-->
</script>
