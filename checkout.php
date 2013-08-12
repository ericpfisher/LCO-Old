<?php

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

$loaner = (isset($_POST['loaner'])) ? $_POST['loaner'] : "Didn't GET that...hahaha!";

?>

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type"
			content="text/html;charset=utf-8" />
		<title> LCO </title>
	</head>
	
	<body>
	<form method="post" action="inc/update_out.inc.php">
	<label>First Name:<input type="text" name="first_name" maxlength="35" /></label>
	<label>Last Name:<input type="text" name="last_name" maxlength="35" /></label>
	<label>Extension:<input type="text" name="user_ext" maxlength="4" /></label>
	<label>Location:<input type="text" name="user_loc" maxlength="6" value="" /></label>
	<input type="hidden" name="asset_tag" value="<?php echo $loaner ?>" />
	<input type="submit" name="submit" value="Checkout!" />
	</form>

	<a href="./index.php?view=loaners&display=list">Back to Loaner List</a>
	
	</body>
</html>