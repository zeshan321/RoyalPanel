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
				$_SESSION['mcuser'] = uuid_to_username($row["uuid"]);
				$_SESSION['uuid'] = format_uuid($row["uuid"]);
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
	
	if ($_SESSION['login'] == true) {
		// perm check
		
		$query = "select * from users";
		$result = mysqli_query($GLOBALS['con'], $query) or die('error');
		
		if (mysqli_num_rows($result)) {
			while($row = $result->fetch_assoc()) { 
				array_push($rows, $row);
			}
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
?>