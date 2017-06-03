<?php
//Database configuration
$server='localhost';
$db_user='root';
$db_pwd='';
$db_name='royalpanel';

/*

$server='149.56.242.146';
$db_user='litebans';
$db_pwd='4jXQHSBsDRWdvs4rvYMzTe';
$db_name='Willow';

*/

// Servers
$server_names = array("Factions", "Skywars");
$server_address = array("99.244.112.35:4444", "99.244.112.35:4445");

// Do not touch
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
or die ("Could not connect to mysql because ".mysqli_error());

mysqli_select_db($con,$db_name)  //select the database
or die ("Could not select to mysql because ".mysqli_error());
?>
