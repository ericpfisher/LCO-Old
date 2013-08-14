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
	
	$sql = "SELECT id, kind, asset_tag, serial_num, os_version, issues FROM Loaners WHERE checked_in=?";
	
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
		$sql = "SELECT * FROM Entries ORDER BY checked_out DESC";
		
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

/*function formatTime($db, $entries_list)
{
	foreach($entries_list as $entry)
	{
		$sql = "SELECT DATE_FORMAT(" . $entry['checked_out'] . ", 'Checked out on %a, %D %M %Y at %r')";

		$stmt = $db->prepare($sql);
		$stmt->execute(array());

		$formatted_entries = NULL;

		while($row = $stmt->fetch())
		{
			$formatted_entries[] = $row;
		} // ends while($row = $stmt->fetch())
	} // ends foreach($entries_list as $entry)

	return $formatted_entries;
} // ends function
*/

function createUserForm()
{
	return <<<FORM
<form action="inc/add_user.inc.php" method="post">
	<fieldset>
		<legend>Create a new user</legend>
		<label>Username:
			<input type="text" name="username" maxlength="75" />
		</label>
		<label>Password:
			<input type="password" name="password" />
		</label>
		<input type="submit" name="submit" value="Create" />
		<input type="submit" name="submit" value="Cancel" />
		<input type="hidden" name="action" value="createuser" />
	</fieldset>
</form>
FORM;
		
}
?>