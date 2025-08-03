<?php 
//start session
session_start();
//create constant to store non repeating values
define('SITEURL', 'http://localhost/SchoolProject/');
define('DB_SERVER', 'localhost');
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'schoolroutinedb');
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) /* or die(mysqli_error()) */; //Database connection
$db_select = mysqli_select_db($conn, DB_NAME) /* or die(mysqli_error()); */  //Selecting Database
?>