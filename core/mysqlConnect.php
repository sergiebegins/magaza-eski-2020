<?php

$mysqli = new mysqli("localhost","root","","arStore2");
$mysqli -> query("SET NAMES UTF8");

if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
