<?php

session_start();

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

date_default_timezone_set('America/Chicago');

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

$asset_tag = (isset($_GET['asset_tag'])) ? $_GET['asset_tag'] : NULL;

list($mac_count, $pc_count) = loanerCount($db);

if($_GET['view']=='loaners')
{
	$kind = (isset($_GET['kind'])) ? $_GET['kind'] : NULL;

	$checked_in = (isset($_GET['checked_in'])) ? $_GET['checked_in'] : NULL;

	$l = getLoaners($db, $kind, $asset_tag, $checked_in);

	$display = array_pop($l);	
}	
elseif($_GET['view']=='checkouts')
{
	$id = (isset($_GET['id'])) ? $_GET['id'] : NULL;

	$l = getCheckouts($db, $asset_tag, $id);

	$display = array_pop($l);

	if(isset($_POST['search_params']))
	{
		$checkout_search_result = searchCheckouts($db, $_POST['search_params']);
	}
	else
	{
		$checkout_search_result = NULL;
	}
}	
else
{	
	header('Location: /lco/index.php');
	$display = NULL;
}

?> <!--- START HTML -->

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
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
				<br /><li><a class="button" href="./admin.php">Add New Tech</a></li>
			<?php endif; ?>
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<br /><a class="button" href="./feedback.php">Submit Feedback</a>
			<?php endif; ?>
				<br /><br /><li><p style="font-size:16px;text-decoration:underline;">Available Loaners</p></li>
				<li><p style="font-size:14px;">Macs: <?php echo $mac_count[0] ?></p></li>
				<li><p style="font-size:14px;">PCs: <?php echo $pc_count[0] ?></p></li>
			</ul>
		</div>

<?php 
	if($_GET['view']=='loaners')
	{		 
		if($display=="info")
		{
			$loaner = $l[0];			
?>	<!--- START HTML -->
		<div id="content">	
	<h3>Loaner Detail: <?php echo $loaner['asset_tag'] ?></h3><br />	
		<p><?php echo "Mac or PC?: " . $loaner['kind']?></p>
	   	<p><?php echo "Serial Number: " . $loaner['serial_num']?></p>
	   	<p><?php echo "OS Version: " . $loaner['os_version']?></p>
	   	<p><?php echo "Issues: " . $loaner['issues']?></p><br />

<!--	   	Testing out displaying loaner info in a table.  Don't think I like it. 
<table border="1">
	   		<caption>Loaner Detail</caption>
	   		<tr>
	   			<th>Asset Tag</th>
	   			<th>Kind</th>
	   			<th>Serial Number</th>
	   			<th>Operating System</th>
	   		</tr>
	   		<tr>
	   			<td><?php echo $loaner['asset_tag'] ?></td>
	   			<td><?php echo $loaner['kind'] ?></td>
	   			<td><?php echo $loaner['serial_num'] ?></td>
	   			<td><?php echo $loaner['os_version'] ?></td>
	   		</tr>
	   	</table>
	   	<br /><p><?php echo "Issues: " . $loaner['issues']?></p> -->
<?php
			if(($loaner['checked_in'])=="0")
			{
?>	<!--- START HTML -->
				<script type="text/javascript">
					alert('NOTICE: this loaner is currently checked out!');
				</script>	
				<p style="color:red;font-size:20px">Loaner is currently checked out.<br /></p>
				<form method="post" action="inc/update_in.inc.php">
					<input type="hidden" name="asset_tag" value="<?php echo $loaner['asset_tag'] ?>" />
					<input type="hidden" name="checked_in" value="<?php echo $loaner['checked_in'] ?>" />
				<?php 	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
					<input type="hidden" name="tech" value="<?php echo $_SESSION['username'] ?>" /> 			
					<input type="submit" name="submit" value="Check In Loaner" onclick="return areYouSure('Check in this loaner?')" />
				<?php endif; ?>
				</form>
<?php
			} // ends if($loaner['checked_in'])=="0"
			else
			{
?>	<!--- START HTML -->
				<p style="color:green;font-size:20px">Loaner is available for checkout.<br /></p>
				<form method="post" action="checkout.php">
					<input type="hidden" name="loaner" value="<?php echo $loaner['asset_tag'] ?>" />
				<?php 	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
					<input type="submit" name="submit" value="Check Out Loaner" />
					<input type="submit" name="submit" value="Edit Loaner Info" />
				<?php endif; ?>
				</form>
		</div>			
<?php
			}// end else (if loaner is checked out)	
		} // ends if($display=="info")

		elseif($display=="checked")
		{
?>	<!--- START HTML -->
			<div id="content">
				<h4>Select A Loaner</h4>
				<form id="loaner_select_form" method="get">
					<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>"/>
					<input type="hidden" name="display" value="checked" />
					<input type="radio" id="in" name="checked_in" value="1" onclick="this.form.submit();"/>Checked In
					<input type="radio" id="out" name="checked_in" value="0" onclick="this.form.submit();" />Checked Out
					<script>
    					var checked_in = getQueryVariable("checked_in");

    					var in_button = document.getElementById("in");
    					var out_button = document.getElementById("out");

    					if(checked_in==0)
    					{
    						out_button.checked = true;
    					}
    					else
    					{
    						in_button.checked = true;
    					}
					</script>
				</form>
				<form action="./index.php" method="get">
					<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>" />
					<input type="hidden" name="display" value="info" />
					<select id="loaner_select">
<?php

					foreach($l as $loaner)
					{
?>	<!--- START HTML -->

						<option id="loaner_select_items" value="<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'] . " - " . $loaner['kind'] ?></option>
	
<?php
					} // ends foreach($l as $loaner)
?>	<!--- START HTML -->
					<input type="submit" name="submit" value="View Details" />
					</select>
				</form>
			</div>

<?php
		} // ends elseif($display=="checked")
		else
		{
?>	<!--- START HTML -->
			<div id="content">
				<h4>Select A Loaner</h4>
				<form action="./index.php" method="get">
					<input type="hidden" name="view" value="<?php echo $_GET['view'] ?>" />
					<input type="hidden" name="display" value="<?php echo $display ?>" />
					<select name="asset_tag">
<?php
					foreach($l as $loaner)
					{
?>	<!--- START HTML -->
				
						<option value="<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'] . " - " . $loaner['kind'] ?></option>	

<?php
					} // ends foreach($l as $loaner)
?>	<!--- START HTML -->
	
					<input type="submit" name="submit" value="View Details" />
					</select>
				</form>
			</div>
<?php
	
		} // ends else $display=='info'
	} // ends if $_GET['view']=='loaners'

	elseif($_GET['view']=='checkouts') 
	{
echo <<<EOT
			<div id="content">
				<fieldset>
					<form method="post" action="index.php?view=checkouts">
						<label>Search All Checkouts:
							<input type="text" name="search_params"/>
							<input type="submit" name="submit" value="Go!" /><p style="font-size:12px">NOTE: Separate search terms using a comma + a space, e.g. (, ).
						</label>
					</form>
				</fieldset>
			</div>

EOT;
		if($display=="checkout detail")
		{
			$entry = $l[0];
		
?>	<!--- START HTML -->
			<div id="content">
				<h4>Checkout Details: Loaner <?php echo $entry['asset_tag'] ?></h4>
					<p><?php echo "Out: " . date('F jS \a\t g:i A', strtotime($entry['checked_out'])) ?></p>
					<p><sub><?php echo "Checked out by: " . $entry['tech_out'] ?></sub></p>
				<?php if($entry['checked_in']): ?>
					<p><?php echo "In: " . date('F jS \a\t g:i A', strtotime($entry['checked_in'])) ?></p>
					<p><sub><?php echo "Checked out by: " . $entry['tech_in'] ?></sub></p>
				<?php endif; ?>
					<p><?php echo "User: " . $entry['first_name'] . " " . $entry['last_name']?></p>
					<p><?php echo "User's Extension: " . $entry['user_ext']?></p>
					<p><?php echo "User's Location: " . $entry['user_loc']?></p>
			</div>
<?php

		} // ends if($display=="checkout detail")

		elseif(isset($_POST['search_params'])) 
		{
			echo "<div id=\"content\">";
			if($checkout_search_result)
			{
				foreach($checkout_search_result as $checkout_entry)
				{
?>	<!--- START HTML -->
					<a href="./index.php?view=checkouts&asset_tag=<?php echo $checkout_entry['asset_tag'] ?>&id=<?php echo $checkout_entry['id'] ?>" style="font-size:14px"><?php echo $checkout_entry['asset_tag'] . ": (" . date('F jS \a\t g:i A', strtotime($checkout_entry['checked_out'])) . ")<br /><br />" ?></a>
<?php
				} // ends foreach($checkout_search_result as $checkout_entry)
			} // ends if($checkout_search_result)
			else
			{
				echo "<p style='padding-left:4em;'>No results to display. Try your search again.</p>";
			}	
		} // ends elseif(isset($_POST['search_params']))
		else
		{

		} // ends else

	} // ends elseif($_GET['view']=='checkouts')
?>	<!--- START HTML -->

		<br /><br />
<!--			<a href="./index.php?view=checkouts">All Checkouts</a><p>		</p>
				<a href="./index.php?view=loaners&display=checked&checked_in=1">Available Loaners</a><p>		</p>
				<a href="./index.php?view=loaners&display=checked&checked_in=0">Checked-Out Loaners</a><p>		</p>   
				<a href="./index.php?view=loaners&display=list">All Loaners</a>
-->

	</body>
</html>