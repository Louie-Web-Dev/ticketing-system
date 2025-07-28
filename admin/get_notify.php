<?php
require_once "database.php";

$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM concerns WHERE status = 'pending'";
$result = $conn->query($sql);

echo $result->num_rows;

$conn->close();
