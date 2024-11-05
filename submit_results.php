<?php
// submit_results.php

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

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];

    // Validate student name
    $stmt = $conn->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->bind_param("s", $student_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Student exists, proceed to insert results
        $stmt = $conn->prepare("INSERT INTO student_results (student_name, maths_score, maths_comments, english_score, english_comments, shona_score, shona_comments, general_paper_score, general_paper_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisssiis", 
            $student_name, 
            $_POST['maths_score'], 
            $_POST['maths_comments'], 
            $_POST['english_score'], 
            $_POST['english_comments'], 
            $_POST['shona_score'], 
            $_POST['shona_comments'], 
            $_POST['general_score'], 
            $_POST['general_comments']
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo "Results posted successfully! <a href='show_results.php'>View Results</a>";
        } else {
            echo "Error saving to database: " . $stmt->error;
        }
    } else {
        // Invalid student name
        echo "Invalid student name. Please ensure the name matches one in the database.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
