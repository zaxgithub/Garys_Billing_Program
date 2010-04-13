<?php require_once(SITE_PATH."\\functions\\utility.functions.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo DEFAULT_TITLE; ?></title>



<?php include_once(SITE_PATH."\\includes\\base.js.php"); ?>

</head>
<body onload="startclock()">
<div id="container">
	<div id="nav">
		<div class="avatar">
			<img src="<?php echo getAvatarSrc(); ?>" alt="<?php echo getUserFullname(); ?>" title="<?php echo getUserFullname(); ?>" border="0" />
		</div>
		<div id="dateBar">
			Welcome <?php echo $_SESSION['emp_nickname']; ?>!<br />
			<div id="jsclock"></div>
		<ul>
			<li class="first"i><a href="import.php">IMPORT</a></li>
			<?php if ($_SERVER['PHP_SELF'] != SITE_PATH."\\login.php") { ?> 
				<li><a href="logout.php" title="LogOut your user">LOGOUT</a></li>
			<?php } else { ?>
				<li><a href="login.php" title="LogIn your user">LOGIN</a></li>
			<?php } ?>
			<li><a href="mailto:<?php echo ADMIN_EMAIL; ?>">EMAIL <?php echo strtoupper(ADMIN_NAME); ?></a></li>
		</ul>
		</div>

	</div>

	<div id="logo"><a href="index.php"><img src="images/admin.logo.gif" width="300" height="49" alt="Home" hspace="0" vspace="0" border="0" /></a></div>
