<?php
// Database configuration
$server='localhost';
$db_user='root';
$db_pwd="U+Sd@k_,g'|F,*6c#5Rp\"+";
$db_name='Willow';

// Lite bans database configuration
$lite_server='localhost';
$lite_db_user='root';
$lite_db_pwd="U+Sd@k_,g'|F,*6c#5Rp\"+";
$lite_db_name='litebans1';

// Servers
$server_names = array("Test server", "Hub", "Skyblock", "OP Factions (CE)");
$server_address = array("royalcraft.co:4444", "royalcraft.co:4445", "royalcraft.co:4446", "royalcraft.co:4447");

// Main DB. Do not touch
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
or die ("Could not connect to mysql because ".mysqli_error());

mysqli_select_db($con,$db_name)  //select the database
or die ("Could not select to mysql because ".mysqli_error());

// Main DB. Do not touch
$lite_con = mysqli_connect($lite_server, $lite_db_user, $lite_db_pwd, $lite_db_name) //connect to the database server
or die ("Could not connect to mysql because ".mysqli_error());

mysqli_select_db($lite_con, $lite_db_name)  //select the database
or die ("Could not select to mysql because ".mysqli_error());
?>
