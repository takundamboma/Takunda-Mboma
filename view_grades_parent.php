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

$grades_records = [];
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

        // Fetch grades and reports for the student
        $stmt = $conn->prepare("SELECT subject, grade, comments, report_date FROM student_reports WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $grades_records[] = $row;
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
    <title>View Child's Grades and Reports</title>
</head>
<body>

<h1>View Child's Grades and Reports</h1>
<?php if (!empty($error_message)): ?>
    <p><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="view_grades_parent.php" method="POST">
    <label for="child_name">Enter Child's Name:</label>
    <input type="text" id="child_name" name="child_name" required>
    <input type="submit" value="View Grades">
</form>

<?php if (!empty($grades_records)): ?>
    <h2>Grades and Reports</h2>
    <table>
        <tr>
            <th>Subject</th>
            <th>Grade</th>
            <th>Comments</th>
            <th>Report Date</th>
        </tr>
        <?php foreach ($grades_records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['subject']); ?></td>
                <td><?php echo htmlspecialchars($record['grade']); ?></td>
                <td><?php echo htmlspecialchars($record['comments']); ?></td>
                <td><?php echo htmlspecialchars($record['report_date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>