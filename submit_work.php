<?php
// submit_work.php

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

// Check if file was uploaded
if (isset($_FILES['document'])) {
    // Check for upload errors
    if ($_FILES['document']['error'] == UPLOAD_ERR_OK) {
        $title = $_POST['title'];
        $description = $_POST['description'];

        // File handling
        $fileTmpPath = $_FILES['document']['tmp_name'];
        $fileName = $_FILES['document']['name'];
        $fileSize = $_FILES['document']['size'];
        $fileType = $_FILES['document']['type'];

        // Define allowed file types
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ];

        // Validate file type
        if (in_array($fileType, $allowedTypes)) {
            // Specify the upload directory (use absolute path)
            $uploadDir = __DIR__ . '/uploads/'; // Absolute path
            $destPath = $uploadDir . basename($fileName);

            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move the uploaded file
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO schoolwork (title, description, document) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $title, $description, $fileName);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "Schoolwork posted successfully! <a href='view_work.php'>View Schoolwork</a>";
                } else {
                    echo "Error saving to database: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "There was an error moving the uploaded file.";
            }
        } else {
            echo "Invalid file type. Please upload a PDF or Word document.";
        }
    } else {
        echo "File upload error: " . $_FILES['document']['error'];
    }
} else {
    echo "No file uploaded.";
}

$conn->close();
