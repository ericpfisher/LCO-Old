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
		<script src="inc/functions.inc.js"></script>
	</head>
	
	<body>
		<div id="menu">
			<h3 id="header">LCO: Loaner Checkout</h3>
			
			<ul id="menu">
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<li><p style="font-size:12px">You are logged in as <?php echo $_SESSION['username'] ?>!</p></li>
				<li><a class="button" href="inc/login.inc.php?action=logout">Log Out</a></li><br />
			<?php else: ?>
				<li><a class="button" href="admin.php">Log In</a></li><br />
			<?php endif; ?>
				<li><a class="button" href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a></li><br />
				<li><a class="button" href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a></li><br />
			<!--<li><a href="./index.php?view=loaners&display=list">All Loaners</a></li> -->
				<li><a class="button" href="./index.php?view=checkouts">Search Checkouts</a></li><br />
			<?php if(isset($_SESSION['username']) && $_SESSION['username']=='lcoadmin'): ?>
				<li><a class="button" href="./admin.php">Add New Tech</a></li>
			<?php endif; ?>
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<br /><a class="button" href="./feedback.php">Submit Feedback</a>
			<?php endif; ?>
			</ul>
		</div>
	
		<div id="content">
			<form method="post" action="inc/update_out.inc.php">
				<label>First Name:<input type="text" name="first_name" placeholder="First" maxlength="35" /></label><br />
				<label>Last Name:<input type="text" name="last_name" placeholder="Last" maxlength="35" /></label><br />
				<label>Extension:<input type="text" name="user_ext" placeholder="1234" maxlength="4" /></label><br />
				<label>Location:<input type="text" name="user_loc" placeholder="5.123" maxlength="10" value="" /></label><br /><br />
				<input type="hidden" name="asset_tag" value="<?php echo $loaner ?>" />
				<input type="hidden" name="tech" value="<?php echo $_SESSION['username'] ?>" />
				<input type="submit" name="submit" value="Checkout!" onclick="return areYouSure('Checkout this loaner?')"/>
				<input type="submit" name="submit" value="Cancel" />
			</form>
		</div>

	</body>
</html>