<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkin'])) {
    $sql = "INSERT INTO attendance (user_id, checkin_time) VALUES ('$user_id', NOW())";
    $conn->query($sql);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $sql = "UPDATE attendance SET checkout_time=NOW() WHERE user_id='$user_id' AND checkout_time IS NULL";
    $conn->query($sql);
}

$sql = "SELECT * FROM attendance WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <form method="POST" action="">
            <button type="submit" name="checkin">Check-In</button>
            <button type="submit" name="checkout">Check-Out</button>
        </form>
        <h3>Attendance</h3>
        <table>
            <tr>
                <th>Check-In Time</th>
                <th>Check-Out Time</th>
                <th>Duration</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['checkin_time']; ?></td>
                    <td><?php echo $row['checkout_time']; ?></td>
                    <td>
                        <?php
                        if ($row['checkout_time']) {
                            $checkin = new DateTime($row['checkin_time']);
                            $checkout = new DateTime($row['checkout_time']);
                            $interval = $checkin->diff($checkout);
                            echo $interval->format('%h hours %i minutes');
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
