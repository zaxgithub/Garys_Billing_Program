<?php 
session_start(); 
ob_start();

error_reporting (E_ALL ^ E_NOTICE);

require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\classes\\auth.class.php");
require_once(realpath(dirname(__FILE__))."\\functions\\validation.functions.php");

$auth = new Authentication();
//$auth->setAuthenticated(false,"","","","","");

$errors = array();

if (isset($_POST['btnLogIn'])) { 
	$errors = findErrors($_POST);
	if (sizeof($errors) == 0){
		if ($auth->authenticateUser($_POST['emp_login'],$_POST['emp_password'])){
				header("Location: ".SITE_URL."/index.php");
		} else {
			$loginError = "invalid login";
		}
	}
}
require_once(realpath(dirname(__FILE__))."\\classes\\auth.class.php");
?>
<?php include_once(realpath(dirname(__FILE__))."\\includes\\header.php"); ?>

	<div id="intQuad">
	
		<h2>LOG IN</h2>
		<div id="intQuadOuter">
			<div id="intQuadInner">
<!-- <img src="<?php echo SITE_URL."/images/login/scrooge-mcduck.gif"; ?>" width="100" height="88" alt="Scrooge McDuck" style="float:left;margin:0px;" border="0" /> -->
				<form action="login.php" method="POST">
				<table border="0" align="center">
				<tr><td><p>Login:</p></td><td><input type="text" name="emp_login" value="<?php echo $_POST['emp_login'] ?>"></td></tr>
				<tr><td colspan="2"><?php echo errorLabel($errors["emp_login"]["required"]); ?></td></tr>
				<tr><td><p>Password:</p></td><td><input type="password" name="emp_password" value="<?php echo $_POST['emp_password'] ?>"><br/></td></tr>
				<tr><td colspan="2"><?php echo errorLabel($errors["emp_password"]["required"]); ?></td></tr>
				<tr><td>&nbsp;</td><td align="left"><input type="submit" name="btnLogIn" value="LOG IN" class="button"></td>
				</tr>
				</table>
				<span class="error"><?php echo $loginError; ?></span>
				</form>
			
			</div>
		</div>
	
	</div>

<?php require_once(realpath(dirname(__FILE__))."\\includes\\footer.php"); ?>