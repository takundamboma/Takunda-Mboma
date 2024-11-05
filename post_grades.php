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

$success_message = "";
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = trim($_POST['student_name']);
    $subject = trim($_POST['subject']);
    $grade = trim($_POST['grade']);
    $comments = trim($_POST['comments']);
    $report_date = date('Y-m-d');

    // Fetch the student's ID based on the name
    $stmt = $conn->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->bind_param("s", $student_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $student_id = $student['id'];

        // Insert the grade and report
        $stmt = $conn->prepare("INSERT INTO student_reports (student_id, subject, grade, comments, report_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $student_id, $subject, $grade, $comments, $report_date);

        if ($stmt->execute()) {
            $success_message = "Grades and reports submitted successfully!";
        } else {
            $error_message = "Error submitting grades: " . $stmt->error;
        }
    } else {
        $error_message = "No student found with the name: " . htmlspecialchars($student_name);
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
    <title>Post Grades and Reports</title>
</head>

<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 20px;
}

h1 {
    color: #333;
    text-align: center;
}

p {
    text-align: center;
    color: #d9534f; /* Error message color */
}

form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #5bc0de;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #31b0d5;
}

@media (max-width: 600px) {
    form {
        padding: 15px;
    }

    input[type="submit"] {
        font-size: 14px;
    }
}
</style>

<body>

<h1>Post Grades and Reports</h1>
<?php if (!empty($success_message)): ?>
    <p><?php echo htmlspecialchars($success_message); ?></p>
<?php elseif (!empty($error_message)): ?>
    <p><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="post_grades.php" method="POST">
    <label for="student_name">Student Name:</label>
    <input type="text" id="student_name" name="student_name" required>
    <br><br>
    <label for="subject">Subject:</label>
    <input type="text" id="subject" name="subject" required>
    <br><br>
    <label for="grade">Grade:</label>
    <input type="text" id="grade" name="grade" required>
    <br><br>
    <label for="comments">Comments:</label>
    <textarea id="comments" name="comments"></textarea>
    <br><br>
    <input type="submit" value="Submit Grades">
</form>

</body>
</html>