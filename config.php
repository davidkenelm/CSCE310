<?php

session_start();

$db_host = "localhost";
$db_user = "root";
$db_pass = "password123!";
$db_name = "csce310";

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysql->set_charset("utf8");

?>