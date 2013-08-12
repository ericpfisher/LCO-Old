<?php


include_once 'functions.inc.php';

if($_SERVER['REQUEST_METHOD']=='POST'
	&& !empty($_POST['first_name'])
	&& !empty($_POST['last_name'])
	&& !empty($_POST['user_ext'])
	&& !empty($_POST['asset_tag']))
{

	include_once 'db.inc.php';
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	$sql = "INSERT INTO entries (checked_out, first_name, last_name, user_ext, user_loc, asset_tag)
			values (?, ?, ?, ?, ?, ?)";
			
	$stmt = $db->prepare($sql);
	$stmt->execute(array(NOW(), $_POST['first_name'], $_POST['last_name'], $_POST['user_ext'], $_POST['user_loc'], $_POST['asset_tag']));
	
	$stmt->closeCursor();
// ---------------------------------------------------- Ends INSERT Entry
/*	$sql2 = "UPDATE Loaners SET checked_in=0 WHERE asset_tag=?";

	$stmt2 = $db->prepare($sql2);
	$stmt2->execute(array($_POST['asset_tag']));

	$stmt2->closeCursor();
	
	$id_obj = $db->query("SELECT LAST_INSERT_ID() FROM Entries");
	$id = $id_obj->fetch();
	$id_obj->closeCursor();
	
	$time_sql = "UPDATE Entries SET checked_out=NOW() WHERE ID=?";
	
	$time_stmt = $db->prepare($time_sql);
	$time_stmt->execute(array($id));
	
	$time_stmt->closeCursor();
*/	
	header('Location: /LCO/index.php?view=loaners');
	
}
else
{
	
	header('Location: /LCO/index.php?view=loaners');
	
}
?>