<?php
session_start();
if (!isset($_SESSION["name"]) || $_SESSION["pos"] !== "admin") {
    header("Location: /TSP-System/ticketing-system/");
    exit();
}

require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["status"])) {
    $id = intval($_POST["id"]);
    $status = $_POST["status"];  // Accept dynamic status from form
    $name = $_SESSION["name"];

    if ($status === "done") {
        $date_accomplished = date("Y-m-d H:i:s");
        $sql = "UPDATE concerns 
                SET status = ?, date_accomplished = ?, pic = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $status, $date_accomplished, $name, $id);
    } else {
        // For other statuses like "on-hold"
        $sql = "UPDATE concerns 
                SET status = ?, pic = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $name, $id);
    }

    if ($stmt->execute()) {
        header("Location: pending.php?updated=1");
        exit();
    } else {
        echo "Failed to update concern. " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
