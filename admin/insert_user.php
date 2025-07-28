<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $department = trim($_POST['department']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $pos = isset($_POST['is_admin']) ? 'admin' : ''; // Only using pos column

    // Validate inputs
    if (empty($fullname) || empty($username) || empty($department) || empty($password)) {
        $_SESSION['error_message'] = "All fields are required!";
        header("Location: create_user.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match!";
        header("Location: create_user.php");
        exit();
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "Username already exists!";
        header("Location: create_user.php");
        exit();
    }
    $stmt->close();

    // Insert new user with only pos column
    $stmt = $conn->prepare("INSERT INTO user (name, username, department, password, pos) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $username, $department, $password, $pos);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User registered successfully!";
        header("Location: create_user.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error registering user: " . $conn->error;
        header("Location: create_user.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: create_user.php");
    exit();
}
