<?php
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function login($user, $pass) {
	$query = "select * from users where BINARY username='$user'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
	
	if (mysqli_num_rows($result)) {
		// Store mc username
		while($row = $result->fetch_assoc()) {
			if (password_verify($pass, $row["password"])) {
				$_SESSION['mcuser'] = getMCName("uuid", $row["uuid"]);
				$_SESSION['uuid'] = $row["uuid"];
				$_SESSION['permissions'] = $row["permissions"];
				$_SESSION['pass'] = $row["password"];
				$_SESSION['passw'] = $pass;
				
				return true;
			}
		}
	}
	return false;
}

function changePassword($user, $pass, $newPass) {	
	$newPass = password_hash($newPass, PASSWORD_DEFAULT);
	
	if (login($user, $pass)) {
		$query = "update users SET password='$newPass' where username='$user'";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');

		return $result;
	}
	
	return false;
}

function getAllUsers() {
	$rows = array();
		
	$query = "select * from users";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			array_push($rows, $row);
		}
	}
	
	return $rows;
}

function createUser($user, $pass, $uuid, $email) {
	$pass = password_hash($pass, PASSWORD_DEFAULT);
	
	if (hasPermission("manage-users")) {
		$query = "select * from users where BINARY username='$user'";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
		if (mysqli_num_rows($result)) {
			return false;
		} else {
			$query = "insert into users (username, password, uuid, email, permissions) VALUES ('$user', '$pass', '$uuid', '$email', '')";
			$result = mysqli_query($GLOBALS['con'], $query) or die('error');

			return $result;
		}
	}
}

function updateUser($user, $pass, $uuid, $email, $permissions) {
	if (hasPermission("manage-users")) {
		$query = "select * from users where BINARY username='$user'";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
		if (!mysqli_num_rows($result)) {
			return false;
		} else {
			if ($pass == "") {
				$query = "update users SET uuid='$uuid', email='$email', permissions='$permissions' where username='$user'";
				$result = mysqli_query($GLOBALS['con'], $query) or die('error');
				
				return $result;
			} else {
				$pass = password_hash($pass, PASSWORD_DEFAULT);
				$query = "update users SET password='$pass', uuid='$uuid', email='$email', permissions='$permissions' where username='$user'";
				$result = mysqli_query($GLOBALS['con'], $query) or die('error');
				
				return $result;
			}
		}
	}
	
	return false;
}

function deleteUser($user) {
	if (hasPermission("manage-users")) {
		$query = "delete from users where BINARY username='$user'";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
		return $result;
	}
	
	return false;
}

function getStatValue($stat) {
	$uuid = $_SESSION['uuid'];
	$query = "select * from stats where uuid='$uuid' and stat='$stat'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
	
	if (mysqli_num_rows($result)) {

		while($row = $result->fetch_assoc()) {
			return $row["statvalue"];
		}
		
	}
	
	return 0;
}

function getStatValueByUUID($stat, $uuid) {
	$query = "select * from stats where uuid='$uuid' and stat='$stat'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
	
	if (mysqli_num_rows($result)) {

		while($row = $result->fetch_assoc()) {
			return $row["statvalue"];
		}
	}
	
	return 0;
}

function getPlayerCount() {
	$rows = array();
	
	$query = "select * from playercount";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			array_push($rows, $row);
		}
	}
	
	return $rows;
}

function hasPermission($permissions) {
	if(strpos($_SESSION['permissions'], $permissions) !== false) {
		return true;
	}
	
	return false;
}

function getWikiPages() {
	if (hasPermission("create-pages")) {
		$rows = array();
			
		$query = "select * from wiki";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');
			
		if (mysqli_num_rows($result)) {
			while($row = $result->fetch_assoc()) { 
				array_push($rows, $row);
			}
		}
		
		return $rows;
	}
}

function createWikiPage($title, $owner, $settings, $content) {
	if (hasPermission("create-pages")) {
		$query = "insert into wiki (title, owner, settings, content) VALUES ('$title', '$owner', '$settings', '$content')";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');

		return $result;
	}
}

function editWikiPage($id, $title, $owner, $settings, $content) {
	if (hasPermission("edit-pages")) {
		$query = "update wiki SET title='$title', owner='$owner', settings='$settings', content='$content' where id='$id'";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');

		return $result;
	}
}

function deleteWikiPage($id) {
	if (hasPermission("delete-pages")) {
		$query = "delete from wiki where id='$id'";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
		return $result;
	}
}

function getWikiByID($id) {
	$query = "select * from wiki where id='$id'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			return $row;
		}
	}
}

function getBans($type, $value) {
	$rows = array();

	if (strtolower($type) == "name") {
		$type = "uuid";
		$value = getUUID("name", $value);
	} elseif (strtolower($type) == "uuid") {
		$type = "uuid";
	} elseif (strtolower($type) == "ip") {
		$type = "ip";
	} else {
		$type = "name";
	}
			
	$query = "select * from litebans_bans where $type='$value'";
	$result = mysqli_query($GLOBALS['lite_con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			array_push($rows, $row);
		}
	}
		
	return $rows;
}

function getMutes($type, $value) {
	$rows = array();

	if (strtolower($type) == "name") {
		$type = "uuid";
		$value = getUUID("name", $value);
	} elseif (strtolower($type) == "uuid") {
		$type = "uuid";
	} elseif (strtolower($type) == "ip") {
		$type = "ip";
	} else {
		$type = "name";
	}
			
	$query = "select * from litebans_mutes where $type='$value'";
	$result = mysqli_query($GLOBALS['lite_con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			array_push($rows, $row);
		}
	}
		
	return $rows;
}

function getWarnings($type, $value) {
	$rows = array();

	if (strtolower($type) == "name") {
		$type = "uuid";
		$value = getUUID("name", $value);
	} elseif (strtolower($type) == "uuid") {
		$type = "uuid";
	} elseif (strtolower($type) == "ip") {
		$type = "ip";
	} else {
		$type = "name";
	}
			
	$query = "select * from litebans_warnings where $type='$value'";
	$result = mysqli_query($GLOBALS['lite_con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			array_push($rows, $row);
		}
	}
		
	return $rows;
}

function getKicks($type, $value) {
	$rows = array();

	if (strtolower($type) == "name") {
		$type = "uuid";
		$value = getUUID("name", $value);
	} elseif (strtolower($type) == "uuid") {
		$type = "uuid";
	} elseif (strtolower($type) == "ip") {
		$type = "ip";
	} else {
		$type = "name";
	}
			
	$query = "select * from litebans_kicks where $type='$value'";
	$result = mysqli_query($GLOBALS['lite_con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			array_push($rows, $row);
		}
	}
		
	return $rows;
}

function getUUID($by, $value) {
	$query = "select * from litebans_history where $by='$value'";
	$result = mysqli_query($GLOBALS['lite_con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			return $row["uuid"];
		}
	}
}

function getMCName($by, $value) {
	$query = "select * from litebans_history where $by='$value'";
	$result = mysqli_query($GLOBALS['lite_con'], $query) or die('error');
			
	if (mysqli_num_rows($result)) {
		while($row = $result->fetch_assoc()) { 
			return $row["name"];
		}
	}
}

function getDateValue($date) {
	if ($date == -1) {
		return "Permanent Ban";
	} else {
		$date = $date / 1000;
		return date("d/m/Y H:i:s", $date);
	}
}
?>