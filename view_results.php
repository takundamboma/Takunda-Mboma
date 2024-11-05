<?php
// view_results.php

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

$results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];

    // Fetch results for the student
    $stmt = $conn->prepare("SELECT maths_score, maths_comments, english_score, english_comments, shona_score, shona_comments, general_paper_score, general_paper_comments FROM student_results WHERE student_name = ?");
    $stmt->bind_param("s", $student_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $results = $result->fetch_assoc();
    } else {
        $error_message = "No results found for the student: $student_name";
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
    <title>View Exam Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .results {
            margin-top: 20px;
        }
        .results table {
            width: 100%;
            border-collapse: collapse;
        }
        .results th, .results td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .results th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>View Exam Results</h1>

<form action="view_results.php" method="POST">
    <label for="student_name">Enter Your Child's Name:</label>
    <input type="text" id="student_name" name="student_name" required>
    <input type="submit" value="View Results">
</form>

<div class="results">
    <?php if (!empty($results)): ?>
        <h2>Results for <?php echo htmlspecialchars($student_name); ?></h2>
        <table>
            <tr>
                <th>Subject</th>
                <th>Score</th>
                <th>Comments</th>
            </tr>
            <tr>
                <td>Maths</td>
                <td><?php echo htmlspecialchars($results['maths_score']); ?></td>
                <td><?php echo htmlspecialchars($results['maths_comments']); ?></td>
            </tr>
            <tr>
                <td>English</td>
                <td><?php echo htmlspecialchars($results['english_score']); ?></td>
                <td><?php echo htmlspecialchars($results['english_comments']); ?></td>
            </tr>
            <tr>
                <td>Shona</td>
                <td><?php echo htmlspecialchars($results['shona_score']); ?></td>
                <td><?php echo htmlspecialchars($results['shona_comments']); ?></td>
            </tr>
            <tr>
                <td>General Paper</td>
                <td><?php echo htmlspecialchars($results['general_paper_score']); ?></td>
                <td><?php echo htmlspecialchars($results['general_paper_comments']); ?></td>
            </tr>
        </table>
    <?php elseif (!empty($error_message)): ?>
        <p><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
</div>

</body>
</html>