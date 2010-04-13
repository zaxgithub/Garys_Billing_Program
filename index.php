<?php 
session_start(); 
ob_start();

error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");


$icon_array = array("emp"=>"EMPLOYEES",
										"client"=>"CLIENTS",
										"svc"=>"SERVICES",
										"bslip"=>"BILLING SLIPS",
										"tslip"=>"TIME SLIPS",
										"billing"=>"BILLING");
?>
<div id="intQuad">
	<h2>HOME</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner" align="center">
				<div style="min-height:300px;vertical-align:middle;text-align:center;width:auto;" align="center">
<img src="<?php echo SITE_URL."/images/dashboard/scrooge_mean.jpg"; ?>" width="183" height="294" alt="Scrooge McDuck- Dashboard" style="float:right;margin:0px;" border="0" />

<script type="text/javascript">
if(!jQuery.browser.mozilla){
	document.write('<div align="center" style="color:red;margin:20px;">You are using an inferior browser.<br />To view this site correctly, please download the FREE and FAST FireFox browser at <a href="http://www.getfirefox.com" target="_blank">www.getfirefox.com</a></div>');
};
</script>


<?php
foreach($icon_array As $k => $v){
?>
<div class="dashboard_icon" style="background:url('<?php echo DASHBOARD_ICON_PATH."/".$k.".jpg"; ?>') no-repeat;cursor:pointer;">
	<a href="<?php echo $k."_view.php"; ?>">
		<div><?php echo $v; ?></div>
	</a>
</div>
<?php
}
?>					

<?php if($_SESSION['emp_id']==4){ ?>
	<img src="<?php echo SITE_URL."/images/JazAndZaK.jpg"; ?>" alt="SBHP SBHP!! PBJ!! PBJ!!" style="clear:both;margin:20px;" border="0" />
<?php } ?>
					
				</div>
		</div>
	</div>
</div>
			
			
<?php 
require_once(realpath(dirname(__FILE__))."\\includes\\footer.php");
?>