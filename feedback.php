<?php

session_start();

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

list($mac_count, $pc_count) = loanerCount($db);

function spamFilter($from_email)
{
	$from_email = filter_var($from_email, FILTER_SANITIZE_EMAIL);

	if(filter_var($from_email, FILTER_VALIDATE_EMAIL))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

?>

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

			<ul id="menu">
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1): ?>
				<li><p style="font-size:12px">You are logged in as <?php echo $_SESSION['username'] ?>!</p></li>
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
<?php

if(isset($_POST['from_email'])
	&& $_POST['submit']=="Send Feedback")
{

	$validEmail = spamFilter($_POST['from_email']);

	if(!$validEmail)
	{
?>
		<script type="text/javascript">
			alert('Invalid email address!');
			window.location = "http://duchess.local/lco/feedback.php";
		</script>
<?php
		
	}
	else
	{
		$from_email = $_POST['from_email'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];

		mail("eric.fisher@us-resources.com", "LCO Feedback: $subject", $message, "From: $from_email");

?>
		<script type="text/javascript">
			alert('Thanks for the feedback!');
			window.location = "http://duchess.local/lco/feedback.php";
		</script>
<?php

	}
	
}
elseif(isset($_POST['submit'])
	&& ($_POST['submit']=="Cancel")) 
{
	header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');
}
else
{
	echo <<<_END
	<div id="content">
		<form method="post" action="feedback.php">
			<fieldset>
				<label>Your Email:
					<input type="email" name="from_email" placeholder="you@example.com"/>
				</label><br />
				<label>Subject:
					<input type="text" name="subject" placeholder="Some Title"/>
				</label><br />
				<label>Feedback:
					<textarea name="message" rows="7" columns="40"></textarea>
				</label><br />
				<input type="submit" name="submit" value="Send Feedback" onclick="return areYouSure('Submit feedback?')" />
				<input type="submit" name="submit" value="Cancel" />
			</fieldset>
		</form>
	</div>
_END;
}

?>

</body>
</html>