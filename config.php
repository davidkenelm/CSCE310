<?php

//session_start();

$db_host = "localhost";
$db_user = "root";
$db_pass = "Nikhilc02!";
$db_name = "310_schema";

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysql->set_charset("utf8");

$_SESSION['sql'] = $mysql;


?>