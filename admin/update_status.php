<?php
session_start();
if (!isset($_SESSION["name"]) || $_SESSION["pos"] !== "admin") {
    header("Location: /TSP-System/ticketing-system/");
    exit();
}

require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    $status = "done";
    $date_accomplished = date("Y-m-d H:i:s");

    $name = $_SESSION["name"];

    $sql = "UPDATE concerns 
            SET status = ?, date_accomplished = ?, pic = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $status, $date_accomplished, $name, $id);

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
