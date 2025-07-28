<?php
require_once "database.php";

$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// FIXED SQL
$sql = "SELECT * FROM concerns WHERE status = 'pending' ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="notification-item">';
        echo '<div class="detail-label">Name: ' . htmlspecialchars($row['name']) . '</div>';
        echo '<div class="detail-label">Department: ' . htmlspecialchars($row['department']) . '</div>';
        echo '<div class="detail-label">Category: ' . htmlspecialchars($row['category']) . '</div>';
        echo '</div>';
    }
} else {
    echo 'No new notifications.';
}

$conn->close();
