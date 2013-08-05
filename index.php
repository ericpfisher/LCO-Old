<?php

include_once 'inc/db.inc.php';
include_once 'inc/functions.inc.php';

$db = new PDO(DB_INFO, DB_USER, DB_PASS);

$kind = (isset($_GET['kind'])) ? $_GET['kind'] : NULL;

$asset_tag = (isset($_GET['asset_tag'])) ? $_GET['asset_tag'] : NULL;

$l = getLoaners($db, $kind, $asset_tag);

$display = array_pop($l);


?>

<html xmlns="http://www.w3.org/1999.xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type"
			content="text/html;charset=utf-8" />
		<title> LCO </title>
		</head>

		<body>
			<?php 
			if($display==2)
			{
			
			$keys = array_keys($l);
			
			foreach($keys as $key){	
				
				
			
			
?>
				
	<p><?php echo $key, " : ", $l[$key], "<br />" ?></p>			
				
<?php

				} // ends foreach($l as $detail)
			
			} // ends if($display==2)

			elseif($display==1)
			{
?>

	# Insert code to display loaners by kind
	
<?php
			} // ends if($display==1)
			
			else
			{
			foreach($l as $loaner){
?>
				
			<a href="./index.php?asset_tag=<?php echo $loaner['asset_tag'] ?>"><?php echo $loaner['asset_tag'], "<br />" ?></a>	

<?php
				} // ends foreach($l as $loaner)

			} // ends else				
?>				   
				<a href="./index.php">Back to Loaner List</a>

		</body>
</html>