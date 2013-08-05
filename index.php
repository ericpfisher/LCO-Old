<?php

include_once 'inc/db.inc.php';

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

$sql = "SELECT kind, asset_tag, issues
		FROM Loaners";

$stmt = $db->prepare($sql);
$stmt->execute(array());

$a = NULL;

while($row = $stmt->fetch()){
			
	$a[] = $row;			

}


?>

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type"
			content="text/html;charset=utf-8" />
		<title> LCO </title>
		</head>

		<body>
			<?php foreach($a as $loaner){
?>
				
				<p><?php echo $loaner['asset_tag'] ?></p>
				
<?php
				   } ?>

		</body>
</html>