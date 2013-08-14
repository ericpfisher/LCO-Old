<?php

session_start();

include_once 'functions.inc.php';

if($_SERVER['REQUEST_METHOD']=='POST'
	&& $_POST['action']=='createuser'
	&& !empty($_POST['username'])
	&& !empty($_POST['password']))
{
	include_once 'db.inc.php';

	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "INSERT INTO Techs (username, password)
			VALUES (?, SHA1(?))";

	$stmt = $db->prepare($sql);
	$stmt->execute(array($_POST['username'], $_POST['password']));

	header('Location: /lco/index.php?view=loaners');
}
else
{
	header('Location: /lco/index.php?view=loaners');
}
?>