<?php
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function login($user, $pass) {
	$query = "select * from users where BINARY username='$user' and password='$pass'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
	
	if (mysqli_num_rows($result)) {
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
?>