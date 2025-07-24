<?php
require_once "database.php";
$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql_concern_pending = "SELECT COUNT(*) AS concern_pending FROM concerns WHERE status LIKE '%pending%'";
$result_concern_pending = $conn->query($sql_concern_pending);

$sql_concern_done = "SELECT COUNT(*) AS concern_done FROM concerns WHERE status LIKE 'done'";
$result_concern_done= $conn->query($sql_concern_done);

if ($result_concern_pending && $result_concern_done) {

    $row_concern_pending = $result_concern_pending->fetch_assoc();
    $count_concern_pending = $row_concern_pending['concern_pending'];

    $row_concern_done = $result_concern_done->fetch_assoc();
    $count_concern_done = $row_concern_done['concern_done'];

    echo '<div class="numbersContainer">';
    echo '  <div>';
    echo '    <label for="pending">Pending Concern</label><br>';
    echo '    <span id="pending">' . $count_concern_pending . '</span>';
    echo '  </div>';
    echo '  <div>';
    echo '    <label for="done">Total Solved</label><br>';
    echo '    <span id="done">' . $count_concern_done . '</span>';
    echo '  </div>';
    echo '</div>';

} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>