<?php
//Database configuration
$server='localhost';
$db_user='root';
$db_pwd='';
$db_name='royalpanel';

// Servers
$server_names = array("Factions", "Skywars");
$server_address = array("localhost:26267", "localhost:26268");

// Do not touch
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
or die ("Could not connect to mysql because ".mysqli_error());

mysqli_select_db($con,$db_name)  //select the database
or die ("Could not select to mysql because ".mysqli_error());
?>
