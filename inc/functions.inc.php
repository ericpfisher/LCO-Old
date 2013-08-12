<?php

function getLoaners($db, $kind=NULL, $asset_tag=NULL, $checked_in=NULL)
{
	if(isset($asset_tag))
	{
	
	$sql = "SELECT id, kind, asset_tag, serial_num, os_version, issues, checked_in FROM Loaners WHERE asset_tag=?";
	
	$stmt = $db->prepare($sql);
	$stmt->execute(array($asset_tag));
	
	$l = NULL;

		while($row = $stmt->fetch()){
			
		$l[] = $row;
		}
		
	$display = "info";
	
	}
	
	elseif(isset($checked_in))
	{
	
	$sql = "SELECT id, asset_tag, serial_num, os_version, issues FROM Loaners WHERE checked_in=?";
	
	$stmt = $db->prepare($sql);
	$stmt->execute(array($checked_in));
	
	$l = NULL;

		while($row = $stmt->fetch()){
			
		$l[] = $row;
		}
		
	$display = "checked";
	
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
	$display = "list";
	
	}
	
	$stmt->closeCursor();

	array_push($l, $display);
	
	return $l;
}

function getCheckouts($db, $asset_tag=NULL, $id=NULL)
{
	if((isset($asset_tag))&&(isset($id)))
	{
		$sql = "SELECT * FROM Entries WHERE id=?";

		$stmt = $db->prepare($sql);
		$stmt->execute(array($id));

		$entries = NULL;

		while($row = $stmt->fetch()){
			$entries[] = $row;
		} // ends "while"

		$display = "checkout detail";
	} // ends "if"

	else
	{
		$sql = "SELECT * FROM Entries";
		
		$stmt = $db->prepare($sql);
		$stmt->execute(array());
		
			$entries = NULL;
		
			while($row = $stmt->fetch()){
				$entries[] = $row;
			} // ends "while
		
		$display = "show all checkouts";
	} // ends "else
	
	$stmt->closeCursor();

	array_push($entries, $display);
	
	return $entries;
} // ends "function"

?>