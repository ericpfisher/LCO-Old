<?php

session_start();

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

list($mac_count, $pc_count) = loanerCount($db);

if($_POST['submit']=='Check Out Loaner')
{
	$loaner = (isset($_POST['loaner'])) ? $_POST['loaner'] : "Didn't GET that...hahaha!";
}
elseif($_POST['submit']=='Edit Loaner Info')
{
	$kind = NULL;
	$asset_tag = $_POST['loaner'];
	$checked_in = NULL;
	$loaner = (getLoaners($db, $kind, $asset_tag, $checked_in));
	$loaner = $loaner[0];
}

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
			<a id="header_link" href="./index.php?view=loaners&display=checked&checked_in=1"><h2 id="header">LCO: Loaner Checkout</h2></a>
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<p style="font-size:12px">You are logged in as <?php echo $_SESSION['username'] ?>!</p><br />
			<?php endif; ?>

			<ul id="menu">
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<li><a class="button" href="inc/login.inc.php?action=logout">Log Out</a></li><br />
			<?php else: ?>
				<li><a class="button" href="admin.php">Log In</a></li><br />
			<?php endif; ?>
				<li><a class="button" href="./index.php?view=loaners&display=checked&checked_in=1">Loaners</a></li><br />
			<!--<li><a class="button" href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a></li><br />
				<li><a href="./index.php?view=loaners&display=list">All Loaners</a></li> -->
				<li><a class="button" href="./index.php?view=checkouts">Search Checkouts</a></li>
			<?php if(isset($_SESSION['username']) && $_SESSION['username']=='lcoadmin'): ?>
				<li><a class="button" href="./admin.php">Add New Tech</a></li>
			<?php endif; ?>
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<br /><a class="button" href="./feedback.php">Submit Feedback</a>
			<?php endif; ?>
				<br /><br /><li><p style="font-size:16px;text-decoration:underline;">Available Loaners</p></li>
				<li><p style="font-size:14px;">Macs: <?php echo $mac_count[0] ?></p></li>
				<li><p style="font-size:14px;">PCs: <?php echo $pc_count[0] ?></p></li>
			</ul>
		</div>
<?php if($_POST['submit']=='Check Out Loaner'): ?>	
		<div id="content">
			<form method="post" action="inc/update_out.inc.php">
				<label>First Name:<input type="text" name="first_name" placeholder="John" maxlength="35" /></label><br />
				<label>Last Name:<input type="text" name="last_name" placeholder="Smith" maxlength="35" /></label><br />
				<label>Extension:<input type="text" name="user_ext" placeholder="1234" maxlength="4" /></label><br />
				<label>Location:<input type="text" name="user_loc" placeholder="5.123" maxlength="10" value="" /></label><br /><br />
				<input type="hidden" name="asset_tag" value="<?php echo $loaner ?>" />
				<input type="hidden" name="tech" value="<?php echo $_SESSION['username'] ?>" />
				<input type="submit" name="submit" value="Checkout" onclick="return areYouSure('Checkout this loaner?')"/>
				<input type="submit" name="submit" value="Cancel" />
			</form>
		</div>
<?php elseif($_POST['submit']=='Edit Loaner Info'): ?>
		<div id="content">
			<form method="post" action="inc/update_out.inc.php">
				<label>Asset Tag:<input type="text" name="asset_tag" placeholder="Enter Asset Tag" value="<?php echo $loaner['asset_tag'] ?>" /></label>
				<label>Kind:<input type="text" name="kind" placeholder="Enter Mac or PC" value="<?php echo $loaner['kind'] ?>" /></label>
				<label>Serial:<input type="text" name="serial_num" placeholder="Enter Serial Number" value="<?php echo $loaner['serial_num'] ?>" /></label>
				<label>OS:<input type="text" name="os_version" placeholder="Enter OS Version" value="<?php echo $loaner['os_version'] ?>" /></label>
				<label>Issues:<textarea name="issues" placeholder="Enter Known Issues" rows="7" columns="40" maxlength="250"><?php echo $loaner['issues'] ?></textarea></label>
				<input type="hidden" name="id" value="<?php echo $loaner['id'] ?>" />
				<input type="submit" name="submit" value="Save Changes" onclick="return areYouSure('Are you sure you wish to save changes?')" />
				<input type="submit" name="submit" value="Cancel" />
			</form>
<?php endif; ?>
	</body>
</html>