<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $sql = "UPDATE attendance SET checkout_time=NOW() WHERE user_id='$user_id' AND checkout_time IS NULL";
    $conn->query($sql);
    header("Location: user.php");
}
?>
