<?php
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE concerns SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: pending.php"); // change to your actual table page
        exit();
    } else {
        echo "Error updating record.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
