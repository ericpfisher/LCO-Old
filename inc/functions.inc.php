<?php

function loanerCount($db)
{
	$mac_sql = "SELECT COUNT(*) FROM Loaners WHERE kind='Mac' AND checked_in=1";
	$pc_sql = "SELECT COUNT(*) FROM Loaners WHERE kind='PC' AND checked_in=1";

	$mac_sql_obj = $db->query($mac_sql);
	$mac_count = $mac_sql_obj->fetch();
	$mac_sql_obj->closeCursor();

	$pc_sql_obj = $db->query($pc_sql);
	$pc_count = $pc_sql_obj->fetch();
	$pc_sql_obj->closeCursor();

	return array($mac_count, $pc_count);

}

function getLoaners($db, $kind=NULL, $asset_tag=NULL, $checked_in=NULL)
{
	if(isset($asset_tag))
	{
	
		$sql = "SELECT id, kind, asset_tag, serial_num, os_version, issues, checked_in FROM Loaners WHERE asset_tag=?";
	
		$stmt = $db->prepare($sql);
		$stmt->execute(array($asset_tag));
	
		$l = NULL;

		while($row = $stmt->fetch())
		{
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

		while($row = $stmt->fetch())
		{
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

		while($row = $stmt->fetch())
		{
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

		while($row = $stmt->fetch())
		{
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
		
			while($row = $stmt->fetch())
			{
				$entries[] = $row;
			} // ends "while
		
		$display = "show all checkouts";
	} // ends "else
	
	$stmt->closeCursor();

	array_push($entries, $display);
	
	return $entries;
} // ends "function"

function searchCheckouts($db, $search_params=NULL)
{
	if(isset($search_params)):
	
		$params_array = explode(", ",$search_params);
	
	endif;

	for($i = 0; $i < count($params_array); $i++)
	{
		if($i==0 || $i==(count($params_array) - 1))
		{
			$params_string = $params_array[$i];
		}
		else
		{
			$params_string = "|" . $params_array[$i] . "|";
		}
	}

	$sql = "SELECT * FROM Entries WHERE first_name REGEXP '$params_string'
			UNION
			SELECT * FROM Entries WHERE last_name REGEXP '$params_string'
			UNION
			SELECT * FROM Entries WHERE asset_tag REGEXP '$params_string'
			UNION
			SELECT * FROM Entries WHERE tech_out REGEXP '$params_string'
			UNION
			SELECT * FROM Entries WHERE tech_in REGEXP '$params_string'";

	$stmt = $db->prepare($sql);
	$stmt->execute();

	$search_results = NULL;

	while($row = $stmt->fetch())
	{
		$search_results[] = $row;
	}

	$stmt->closeCursor();

	return $search_results;

}

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