<?php
// view_work.php

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

// Query to fetch schoolwork
$sql = "SELECT id, title, description, document FROM schoolwork";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schoolwork</title>
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

<h1>Schoolwork</h1>

<table>
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Document</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["title"]) . "</td>
                    <td>" . htmlspecialchars($row["description"]) . "</td>
                    <td><a href='uploads/" . htmlspecialchars($row["document"]) . "' target='_blank'>Download</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No schoolwork posted</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>