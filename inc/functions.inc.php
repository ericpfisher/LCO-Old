<?php

function getLoaners($db, $kind=NULL, $asset_tag=NULL)
{
	if(isset($asset_tag))
	{
	
	$sql = "SELECT id, kind, serial_num, os_version, issues, checked_in FROM Loaners WHERE asset_tag=?";
	
	$stmt = $db->prepare($sql);
	$stmt->execute(array($asset_tag));
	
	$l = NULL;

		while($row = $stmt->fetch()){
			
		$l[] = $row;
		}
		
	$display = 2;
	
	}
	
	elseif(isset($kind))
	{
	
	$sql = "SELECT id, asset_tag, serial_num, os_version, issues, checked_in FROM Loaners WHERE kind=?";
	
	$stmt = $db->prepare($sql);
	$stmt->execute(array($kind));
	
	$l = NULL;

		while($row = $stmt->fetch()){
			
		$l[] = $row;
		}
		
	$display = 1;
	
	}
	
	else
	{
	
	$sql = "SELECT id, kind, asset_tag, checked_in FROM Loaners";
	
	$stmt = $db->prepare($sql);
	$stmt->execute(array());
	
		$l = NULL;

		while($row = $stmt->fetch()){
			
		$l[] = $row;			

		}
	$display = 0;
	
	}
	
	array_push($l, $display);
	
	return $l;
}

?>