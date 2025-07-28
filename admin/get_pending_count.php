<?php
include 'database.php';


$sql = "SELECT COUNT(*) AS count FROM concerns 
        WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);
$count = ($result && $row = mysqli_fetch_assoc($result)) ? (int)$row['count'] : 0;

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
