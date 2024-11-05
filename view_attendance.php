<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$attendance_records = [];
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $child_name = trim($_POST['child_name']);

    // Fetch the student's ID based on the name
    $stmt = $conn->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->bind_param("s", $child_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $student_id = $student['id'];

        // Get the start of the week (Monday)
        $start_of_week = date('Y-m-d', strtotime('monday this week'));
        $end_of_week = date('Y-m-d', strtotime('sunday this week'));

        // Fetch attendance records for the week
        $stmt = $conn->prepare("SELECT date, status FROM attendance WHERE student_id = ? AND date BETWEEN ? AND ?");
        $stmt->bind_param("iss", $student_id, $start_of_week, $end_of_week);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $attendance_records[] = $row;
        }
    } else {
        $error_message = "No student found with the name: " . htmlspecialchars($child_name);
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Child's Attendance</title>
</head>
<body>

<h1>View Child's Attendance</h1>
<?php if (!empty($error_message)): ?>
    <p><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="view_attendance.php" method="POST">
    <label for="child_name">Enter Child's Name:</label>
    <input type="text" id="child_name" name="child_name" required>
    <input type="submit" value="View Attendance">
</form>

<?php if (!empty($attendance_records)): ?>
    <h2>Attendance Records for the Week</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php foreach ($attendance_records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['date']); ?></td>
                <td><?php echo htmlspecialchars($record['status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>