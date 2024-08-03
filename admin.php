<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$users_sql = "SELECT * FROM users WHERE role='user'";
$users_result = $conn->query($users_sql);

$attendance_result = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $selected_user_id = $_POST['user_id'];
    $attendance_sql = "SELECT * FROM attendance WHERE user_id='$selected_user_id'";
    $attendance_result = $conn->query($attendance_sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin</h2>
        <form method="POST" action="">
            <label>Select User:</label>
            <select name="user_id">
                <?php while ($user = $users_result->fetch_assoc()) : ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">View Attendance</button>
        </form>
        <h3>Attendance</h3>
        <table>
            <tr>
                <th>Check-In Time</th>
                <th>Check-Out Time</th>
                <th>Duration</th>
            </tr>
            <?php while ($row = $attendance_result->fetch_assoc()) : ?>
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
