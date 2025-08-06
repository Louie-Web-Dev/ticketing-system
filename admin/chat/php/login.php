<?php
session_start();
include_once "database.php";

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($username) && !empty($password)) {
    $sql = mysqli_query($conn, "SELECT * FROM user WHERE username = '{$username}'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        $user_pass = md5($password);
        $enc_pass = $row['password'];
        if ($user_pass === $enc_pass) {
            $status = "Active now";
            $sql2 = mysqli_query($conn, "UPDATE user SET status = '{$status}' WHERE id = {$row['id']}");
            if ($sql2) {
                $_SESSION['id'] = $row['id'];
                echo "success";
            } else {
                echo "Something went wrong. Please try again!";
            }
        } else {
            echo "Username or Password is Incorrect!";
        }
    } else {
        echo "$username - This username does not exist!";
    }
} else {
    echo "All input fields are required!";
}
