<?php

session_start();

include_once 'functions.inc.php';

if($_SERVER['REQUEST_METHOD']=='POST'
	&& $_POST['submit']=='Check In Loaner'
	&& !empty($_POST['asset_tag'])
	)
{
	include_once 'db.inc.php';
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "UPDATE Loaners SET checked_in=1 WHERE asset_tag=?";

	$stmt = $db->prepare($sql);
	$stmt->execute(array($_POST['asset_tag']));

	$stmt->closeCursor();
// -------------------------------------------- END CHANGE CHECKED IN STATUS
	$get_co_sql = "SELECT MAX(id) FROM Entries WHERE asset_tag=" . $_POST['asset_tag'];

	$id_obj = $db->prepare($get_co_sql);
	$id_obj->execute();
	$ids = $id_obj->fetch(PDO::FETCH_BOTH);

	$id = $ids[0];

	$id_obj->closeCursor();

// -------------------------------------------- END GET ID OF LATEST CHECKOUT BY ASSET TAG	
	$time_sql = "UPDATE Entries SET checked_in=NOW(), tech_in=? WHERE id=?";
	
	$time_stmt = $db->prepare($time_sql);
	$time_stmt->execute(array($_SESSION['username'], $id));
	
	$time_stmt->closeCursor();
// -------------------------------------------- END UPDATE CHECKED IN TIMESTAMP

	$_SESSION['status'] = "Loaner checked in successfully!";
	header('Location: /LCO/index.php?view=loaners&display=checked&checked_in=1');
} // ends if

else{
	
	header('Location: /LCO/index.php?view=checkouts');
}

?>