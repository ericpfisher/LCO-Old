<?php

session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1):

include_once 'inc/functions.inc.php';

?>

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="css/default.css"/>
		<script src="inc/functions.inc.js"></script>
		<title> LCO </title>
	</head>

	<body>
		<?php echo createUserForm() ?><br />
		<a href="index.php?view=loaners&display=checked&checked_in=1">Back To LCO</a>
	</body>
</html>

<?php 

else:

?>

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="css/default.css"/>
		<title> LCO: Please Log In </title>
	</head>

	<body>
		<form method="post" action="inc/login.inc.php" enctype="multipart/form-data">
			<fieldset>
				<legend>Please Log In</legend>
				<label>Username:
					<input type="text" name="username" maxlength="75" />
				</label>
				<label>Password:
					<input type="password" name="password" maxlength="150" />
				</label>
				<input type="hidden" name="action" value="login" />
				<input type="submit" name="submit" value="Log In" />
			</fieldset>
		</form>
		<a href="./index.php?view=loaners&display=checked&checked_in=1">Back to LCO</a>
	</body>

</html>

<?php endif; ?>