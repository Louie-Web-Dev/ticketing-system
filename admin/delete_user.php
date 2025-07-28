<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Prevent deleting yourself
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
        $_SESSION['error'] = "You cannot delete your own account!";
        header("Location: view_user.php");
        exit();
    }

    $sql = "DELETE FROM user WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user: " . mysqli_error($conn);
    }

    header("Location: view_user.php");
    exit();
} else {
    header("Location: view_user.php");
    exit();
}
