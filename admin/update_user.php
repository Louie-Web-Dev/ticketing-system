<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Check if username already exists (excluding current user)
    $check_sql = "SELECT id FROM user WHERE username = '$username' AND id != $user_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "Username already exists!";
        header("Location: view_user.php");
        exit();
    }

    // Determine position
    $position = $is_admin ? 'admin' : '';

    $sql = "UPDATE user SET 
            name = '$name',
            username = '$username',
            department = '$department',
            pos = '$position'
            WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating user: " . mysqli_error($conn);
    }

    header("Location: view_user.php");
    exit();
} else {
    header("Location: view_user.php");
    exit();
}
