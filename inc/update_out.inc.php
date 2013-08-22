<?php

session_start();

include_once 'functions.inc.php';

if($_SERVER['REQUEST_METHOD']=='POST'
	&& $_POST['submit']=='Checkout'
	&& !empty($_POST['first_name'])
	&& !empty($_POST['last_name'])
	&& !empty($_POST['user_ext'])
	&& !empty($_POST['asset_tag']))
{

	include_once 'db.inc.php';
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	$sql = "INSERT INTO entries (first_name, last_name, user_ext, user_loc, asset_tag, tech_out)
			values (?, ?, ?, ?, ?, ?)";
			
	$stmt = $db->prepare($sql);
	$query1 = $stmt->execute(array($_POST['first_name'], $_POST['last_name'], $_POST['user_ext'], $_POST['user_loc'], $_POST['asset_tag'], $_POST['tech']));
	
	dbErrorOrClose($stmt, $query1);

	$sql2 = "UPDATE Loaners SET checked_in=0 WHERE asset_tag=?";

	$stmt2 = $db->prepare($sql2);
	$query2 = $stmt2->execute(array($_POST['asset_tag']));

	dbErrorOrClose($stmt2, $query2);
	
	$get_id_sql = "SELECT LAST_INSERT_ID() FROM Entries LIMIT 1";

	$id_obj = $db->prepare($get_id_sql);
	$query3 = $id_obj->execute();

	if(!$query3) // did not use Error Or Close so I can fetch the ID before closing. 
	{
		die("Database error: " . print_r($id_obj->errorInfo()));
		$_SESSION['status'] = "Database error.  Contact your administrator.";
		header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');
	}
	else
	{
		$id = $id_obj->fetch();
		$id_obj->closeCursor();
	}
	
	$time_sql = "UPDATE Entries SET checked_out=NOW() WHERE ID=?";
	
	$time_stmt = $db->prepare($time_sql);
	$query4 = $time_stmt->execute(array($id[0]));
	
	dbErrorOrClose($time_stmt, $query4);

	$_SESSION['status'] = "Loaner checked out successfully!";
	header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');
}
elseif($_SERVER['REQUEST_METHOD']=='POST'
	&& $_POST['submit']=='Save Changes'
	&& !empty($_POST['asset_tag']))
{
	include_once 'db.inc.php';

	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "UPDATE Loaners SET kind=?, asset_tag=?, serial_num=?, os_version=?, issues=? WHERE id=?";

	$stmt = $db->prepare($sql);
	$edit_query = $stmt->execute(array($_POST['kind'], $_POST['asset_tag'], $_POST['serial_num'], $_POST['os_version'], $_POST['issues'], $_POST['id']));

	dbErrorOrClose($stmt, $edit_query);

	$_SESSION['status'] = "Changes saved!";
	header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');
}
else
{
	header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');	
}