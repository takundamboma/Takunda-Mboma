<?php
// show_results.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch results
$sql = "SELECT student_name, maths_score, maths_comments, english_score, english_comments, shona_score, shona_comments, general_paper_score, general_paper_comments FROM student_results ORDER BY student_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Student Results</h1>

<table>
    <tr>
        <th>Student Name</th>
        <th>Maths Score</th>
        <th>Maths Comments</th>
        <th>English Score</th>
        <th>English Comments</th>
        <th>Shona Score</th>
        <th>Shona Comments</th>
        <th>General Paper Score</th>
        <th>General Paper Comments</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["student_name"]) . "</td>
                    <td>" . htmlspecialchars($row["maths_score"]) . "</td>
                    <td>" . htmlspecialchars($row["maths_comments"]) . "</td>
                    <td>" . htmlspecialchars($row["english_score"]) . "</td>
                    <td>" . htmlspecialchars($row["english_comments"]) . "</td>
                    <td>" . htmlspecialchars($row["shona_score"]) . "</td>
                    <td>" . htmlspecialchars($row["shona_comments"]) . "</td>
                    <td>" . htmlspecialchars($row["general_paper_score"]) . "</td>
                    <td>" . htmlspecialchars($row["general_paper_comments"]) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No results posted</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>