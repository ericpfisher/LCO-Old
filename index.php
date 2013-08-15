<?php

session_start();

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

date_default_timezone_set('America/Chicago');

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

$kind = (isset($_GET['kind'])) ? $_GET['kind'] : NULL;

$asset_tag = (isset($_GET['asset_tag'])) ? $_GET['asset_tag'] : NULL;

$id = (isset($_GET['id'])) ? $_GET['id'] : NULL;

$checked_in = (isset($_GET['checked_in'])) ? $_GET['checked_in'] : NULL;

if($_GET['view']=='loaners'){
	$l = getLoaners($db, $kind, $asset_tag, $checked_in);
	$display = array_pop($l);	
}	
elseif($_GET['view']=='checkouts'){
	$l = getCheckouts($db, $asset_tag, $id);
	$display = array_pop($l);
}	
else{
	
	header('Location: /lco/index.php');
	$display = NULL;
}




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
		<p style="font-size:14px">You are logged in as <?php echo $_SESSION['username'] ?></p>
		<a href="inc/login.inc.php?action=logout">Log Out</a>
<?php else: ?>
		<a href="admin.php">Log In</a>
<?php endif; ?>
		<ul id="menu_items">
			<li><a href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a></li>
			<li><a href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a></li>
<!--		<li><a href="./index.php?view=loaners&display=list">All Loaners</a></li> -->
			<li><a href="./index.php?view=checkouts">All Checkouts</a></li>
<?php if(isset($_SESSION['username']) && $_SESSION['username']=='lcoadmin'): ?>
			<li><a href="./admin.php">Add New Tech</a></li>
<?php endif; ?>

		</ul>
	</div>

<?php
if($_GET['view']=='loaners'){		 
	if($display=="info"){
			
	$loaner = $l[0];
						
?>	
	<div id="content">	
	<h4>Loaner Detail: <?php echo $loaner['asset_tag'] ?></h4>	
	<p><?php echo "Kind: " . $loaner['kind']?><br />
	   <?php echo "Serial: " . $loaner['serial_num']?><br />
	   <?php echo "OS Version: " . $loaner['os_version']?><br />
	   <?php echo "Issues: " . $loaner['issues']?></p>	
<?php
		if(($loaner['checked_in'])=="0"){
?>		
		<p style="color:red;font-size:20px">Loaner is currently checked out.<br /></p>
		<form method="post" action="inc/update_in.inc.php">
			<input type="hidden" name="asset_tag" value="<?php echo $loaner['asset_tag'] ?>" />
			<input type="hidden" name="checked_in" value="<?php echo $loaner['checked_in'] ?>" />
<?php 	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
			<input type="hidden" name="tech" value="<?php echo $_SESSION['username'] ?>" /> 			
			<input type="submit" name="submit" value="Check In Loaner" />
<?php endif; ?>
		</form>
<?php
		} // ends if($loaner['checked_in'])=="0"
		else{
?>
		<p style="color:green;font-size:20px">Loaner is available for checkout.<br /></p>
		<form method="post" action="checkout.php">
			<input type="hidden" name="loaner" value="<?php echo $loaner['asset_tag'] ?>" />
<?php 	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
			<input type="submit" name="submit" value="Check Out Loaner" />
<?php endif; ?>
		</form>
	</div>			
<?php
		}// end else (if loaner is checked out)	
	} // ends if($display=="info")

	elseif($display=="checked"){
?>
		<div id="content">
			<h4>Select A Loaner</h4>
			<form action="./index.php" method="get">
				<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>" />
				<input type="hidden" name="display" value="info" />
				<select name="asset_tag">
<?php

		foreach($l as $loaner){
?>

					<option value="<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'] . " - " . $loaner['kind'] ?></option>
	
<?php
		} // ends foreach($l as $loaner)
?>
				<input type="submit" name="submit" value="View Details" />
				</select>
			</form>
		</div>

<?php
	} // ends elseif($display=="checked")
			
	else{
?>
		<div id="content">
			<h4>Select A Loaner</h4>
			<form action="./index.php" method="get">
				<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>" />
				<input type="hidden" name="display" value="<?php echo $display ?>" />
				<select name="asset_tag">
<?php
		foreach($l as $loaner){
?>
				
				<option value="<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'] . " - " . $loaner['kind'] ?></option>	

<?php
		} // ends foreach($l as $loaner)
?>
	
				<input type="submit" name="submit" value="View Details" />
				</select>
			</form>
		</div>
<?php
	
	} // ends else $display=='info'
} // ends if $_GET['view']=='loaners'

elseif($_GET['view']=='checkouts') {
	if($display=="checkout detail")
	{
		$entry = $l[0];
		
?>
	<div id="content">
		<h4>Checkout Details: Loaner <?php echo $entry['asset_tag'] ?></h4>
		<p><?php echo "Out: " . date('F jS \a\t g:i A', strtotime($entry['checked_out'])) . " by " . $entry['tech_out'] ?></p>
<?php if($entry['checked_in']): ?>
		<p><?php echo "In: " . date('F jS \a\t g:i A', strtotime($entry['checked_in'])) . " by " . $entry['tech_in'] ?></p>
<?php endif; ?>
		<p><?php echo "Who Took It: " . $entry['first_name'] . " " . $entry['last_name']?></p>
		<p><?php echo "Their Extension: " . $entry['user_ext']?></p>
		<p><?php echo "Their Location: " . $entry['user_loc']?></p>
	</div>
<?php

	} // ends if($display=="checkout detail")
	else{
?>
	<div id="content">
<?php
		foreach($l as $entry) {
?>
			<a href="./index.php?view=checkouts&asset_tag=<?php echo $entry['asset_tag'] ?>&id=<?php echo $entry['id'] ?>" style="font-size:14px"><?php echo $entry['asset_tag'] . ": (" . date('F jS \a\t g:i A', strtotime($entry['checked_out'])) . ")<br />" ?></a>
			<br />
<?php
		} // ends foreach($l as $entry)
	} // ends else
} // ends elseif($_GET['view']=='checkouts')
?>				
	</div>

<br /><br />
<!--			<a href="./index.php?view=checkouts">All Checkouts</a><p>		</p>
				<a href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a><p>		</p>
				<a href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a><p>		</p>   
				<a href="./index.php?view=loaners&display=list">All Loaners</a>
-->
		</body>
</html>