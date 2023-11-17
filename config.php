<?php

session_start();

$db_host = "localhost";
$db_user = "davidwindisch";
$db_pass = "password";
$db_name = "310_db";

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysql->set_charset("utf8");

?>