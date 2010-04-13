
<form name="order_form" id="order_form" method="POST" post="<?php echo script_name(); ?>">
	<input type="hidden" name="page_num" id="page_num" value="<?php echo $_POST['page_num']; ?>" />
	<input type="hidden" name="page_size" id="page_size" value="<?php echo $_POST['page_size']; ?>" />
	<input type="hidden" name="order_by" id="order_by" value="<?php echo $_POST['order_by']; ?>" />
	<input type="hidden" name="order_dir" id="order_dir" value="<?php echo $_POST['order_dir']; ?>" />
	<input type="hidden" name="search_term" id="search_term" value="<?php echo $_POST['search_term']; ?>" />
	<input type="hidden" name="action" id="action" value="<?php echo $_POST['action']; ?>" />
</form>
