<?php

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

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
	<title> LCO </title>
</head>

<body>
	<h2>LCO: Loaner CheckOut</h2>
	<ul id="menu">
		<li><a href="./index.php?view=checkouts">All Checkouts</a></li>
		<li><a href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a></li>
		<li><a href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a></li>
		<li><a href="./index.php?view=loaners&display=list">All Loaners</a></li>
	</ul>

<?php
if($_GET['view']=='loaners'){		 
	if($display=="info"){
			
	$loaner = $l[0];
						
?>			
	<p><?php echo "Kind: " . $loaner['kind']?><br />
	   <?php echo "Serial: " . $loaner['serial_num']?><br />
	   <?php echo "OS Version: " . $loaner['os_version']?><br />
	   <?php echo "Issues: " . $loaner['issues']?><br />
	   <?php echo "Checked in?: " . $loaner['checked_in']?><br />
	<br />		
<?php
		if(($loaner['checked_in'])=="0"){
?>
		<form method="post" action="inc/update_in.inc.php">
			<input type="hidden" name="asset_tag" value="<?php echo $loaner['asset_tag'] ?>" />
			<input type="hidden" name="checked_in" value="<?php echo $loaner['checked_in'] ?>" />
			<input type="submit" name="submit" value="Check In" />
		</form>
<?php
		} // ends if($loaner['checked_in'])=="0"
		else{
?>
		<form method="post" action="checkout.php">
			<input type="hidden" name="loaner" value="<?php echo $loaner['asset_tag'] ?>" />
			<input type="submit" name="submit" value="Check Out" />
		</form>			
<?php
		}// end else (if loaner is checked out)	
	} // ends if($display=="info")

	elseif($display=="checked"){
?>
		<form action="./index.php" method="get">
		<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>" />
		<input type="hidden" name="display" value="info" />
		<select name="asset_tag">
<?php

		foreach($l as $loaner){
?>

		<option value="<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'] ?></option>
	
<?php
		} // ends foreach($l as $loaner)
?>
		<input type="submit" name="submit" value="Select Loaner" />
		</select>
		</form>

<?php
	} // ends elseif($display=="checked")
			
	else{
?>

		<form action="./index.php" method="get">
		<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>" />
		<input type="hidden" name="display" value="<?php echo $display ?>" />
		<select name="asset_tag">
<?php
		foreach($l as $loaner){
?>
				
		<option value="<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'] ?></option>	

<?php
		} // ends foreach($l as $loaner)
?>
	
		<input type="submit" name="submit" value="Select Loaner" />
		</select>
		</form>
<?php
	
	} // ends else $display=='info'
} // ends if $_GET['view']=='loaners'

elseif($_GET['view']=='checkouts') {
	if($display=="checkout detail")
	{
		$entry = $l[0];
		
?>
		<p><?php echo "Checked out: " . $entry['checked_out']?></p>
		<p><?php echo "Checked in: " . $entry['checked_in']?></p>
		<p><?php echo "User: " . $entry['first_name'] . " " . $entry['last_name']?></p>
		<p><?php echo "Extension: " . $entry['user_ext']?></p>
		<p><?php echo "Location: " . $entry['user_loc']?></p>
		<p><?php echo "Asset tag: " . $entry['asset_tag']?></p>
<?php

	} // ends if($display=="checkout detail")
	else{
		foreach($l as $entry) {
?>
			<a href="./index.php?view=checkouts&asset_tag=<?php echo $entry['asset_tag'] ?>&id=<?php echo $entry['id'] ?>"><?php echo $entry['asset_tag'] . " - " . $entry['checked_out'] . "<br />" ?></a>
			<br />
<?php
		} // ends foreach($l as $entry)
	} // ends else
} // ends elseif($_GET['view']=='checkouts')
?>				<br /><br />
<!--			<a href="./index.php?view=checkouts">All Checkouts</a><p>		</p>
				<a href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a><p>		</p>
				<a href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a><p>		</p>   
				<a href="./index.php?view=loaners&display=list">All Loaners</a>
-->
		</body>
</html>