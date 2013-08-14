<?php

session_start();

include_once 'functions.inc.php';

if($_SERVER['REQUEST_METHOD']=='POST'
	&& $_POST['action']=='login'
	&& !empty($_POST['username'])
	&& !empty($_POST['password']))
{
	include_once 'db.inc.php';

	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "SELECT COUNT(*) AS num_users
			FROM Techs
			WHERE username=?
			AND password=SHA1(?)";

	$stmt = $db->prepare($sql);
	$stmt->execute(array($_POST['username'], $_POST['password']));

	$response = $stmt->fetch();

	if($response['num_users'] > 0)
	{
		$_SESSION['loggedin'] = 1;
		$_SESSION['username'] = $_POST['username'];
	}
	else
	{
		$_SESSION['loggedin'] = NULL;
	}

	header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');
}
elseif ($_GET['action'] == 'logout') 
{
	session_destroy();
	header('Location: /lco/index.php?view=loaners&display=checked&checked_in=1');
	exit;
}
else
{
	header('Location: /lco/index.php?view=loaners&display=checked&checked_in=1');
}

?>