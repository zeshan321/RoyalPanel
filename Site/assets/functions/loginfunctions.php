<?php
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function login($user, $pass) {
	$query = "select * from users where username='$user' and password='$pass'";
	$result = mysqli_query($GLOBALS['con'], $query) or die('error');
	
	if (mysqli_num_rows($result)) {
		return true;
	}
	return false;
}
?>