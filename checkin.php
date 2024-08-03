<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO attendance (user_id, checkin_time) VALUES ('$user_id', NOW())";
    $conn->query($sql);
    header("Location: user.php");
}
?>
