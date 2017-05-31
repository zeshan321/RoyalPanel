<?php
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function login($user, $pass) {
	$query = "select * from users where BINARY username='$user' and password='$pass'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
	
	if (mysqli_num_rows($result)) {
		// Store mc username
		while($row = $result->fetch_assoc()) {
			$_SESSION['mcuser'] = uuid_to_username($row["uuid"]);
		}
		
		return true;
	}
	return false;
}

function changePassword($user, $pass, $newPass) {
	if (login($user, $pass)) {
		$query = "update users SET password='$newPass' where password='$pass'";
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
?>