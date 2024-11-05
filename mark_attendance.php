<?php
session_start();

// Check if the user is logged in as a teacher


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

$error_message = "";
$success_message = "";

// Get today's date
$today = date('Y-m-d');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];
    
    // Check if attendance for today has already been marked
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ? AND date = ?");
    $stmt->bind_param("is", $student_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Attendance for today has already been marked.";
    } else {
        // Insert attendance record
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $student_id, $today, $status);

        if ($stmt->execute()) {
            $success_message = "Attendance marked successfully!";
        } else {
            $error_message = "Error marking attendance: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Fetch students to display in the form
$students = [];
$stmt = $conn->prepare("SELECT id, name FROM students");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
</head>
<body>

<h1>Mark Attendance</h1>
<?php if (!empty($success_message)): ?>
    <p><?php echo htmlspecialchars($success_message); ?></p>
<?php elseif (!empty($error_message)): ?>
    <p><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="mark_attendance.php" method="POST">
    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required>
        <?php foreach ($students as $student): ?>
            <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <label for="status">Attendance Status:</label>
    <select name="status" id="status" required>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
    </select>
    <br><br>
    <input type="submit" value="Mark Attendance">
</form>

</body>
</html>