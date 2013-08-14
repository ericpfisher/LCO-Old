<?php

session_start();

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

$loaner = (isset($_POST['loaner'])) ? $_POST['loaner'] : "Didn't GET that...hahaha!";

?>

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type"
			content="text/html;charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="css/default.css"/>
		<title> LCO </title>
	</head>
	
	<body>
		<div id="menu">
		<h3>LCO: Loaner Checkout</h3>
<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
		<p style="font-size:12px">You are logged in as <?php echo $_SESSION['username'] ?></p>
<?php endif; ?>
		<ul id="menu_items">
			<li><a href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a></li>
			<li><a href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a></li>
			<li><a href="./index.php?view=checkouts">All Checkouts</a></li>
<!--		<li><a href="./index.php?view=loaners&display=list">All Loaners</a></li> -->
			<li><a href="./admin.php">Admin</a></li>
<?php 		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
			<li><a href="inc/login.inc.php?action=logout">Log Out</a></li>
<?php endif; ?>
		</ul>
	</div>
	
	<div id="content">
		<form method="post" action="inc/update_out.inc.php">
			<label>First Name:<input type="text" name="first_name" maxlength="35" /></label><br />
			<label>Last Name:<input type="text" name="last_name" maxlength="35" /></label><br />
			<label>Extension:<input type="text" name="user_ext" maxlength="4" /></label><br />
			<label>Location:<input type="text" name="user_loc" maxlength="6" value="" /></label><br /><br />
			<input type="hidden" name="asset_tag" value="<?php echo $loaner ?>" />
			<input type="hidden" name="tech" value="<?php echo $_SESSION['username'] ?>" />
			<input type="submit" name="submit" value="Checkout!" />
			<input type="submit" name="submit" value="Cancel" />
		</form>

		<a href="./index.php?view=loaners&display=checked&checked_in=1">Back to Available Loaners</a>
	</div>

	</body>
</html>