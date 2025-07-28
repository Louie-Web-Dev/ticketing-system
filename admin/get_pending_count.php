<?php
include 'database.php';

$user = $_GET['user'] ?? '';
$user = mysqli_real_escape_string($conn, $user);

$sql = "SELECT COUNT(*) AS count FROM concerns 
        WHERE status = 'pending' AND name = '$user'";
$result = mysqli_query($conn, $sql);
$count = ($result && $row = mysqli_fetch_assoc($result)) ? (int)$row['count'] : 0;

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
