<?php
$hostName   = "localhost";
$dbUser     = "root";
$dbPassword = "";
$dbName     = "ts_db";

$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
