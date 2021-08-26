<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME','cmsc433');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

$sql = "CREATE DATABASE IF NOT EXISTS cmsc433";
mysqli_query($link, $sql);

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(mysqli_connect_errno()){
	die("ERROR Could not connect. " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS exam2(
		examID INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        examName VARCHAR(20) NOT NULL ,
        examPoints INT(6)
)";

$result = mysqli_query($link,$sql);

?>