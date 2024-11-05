*+-/<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parent_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize session
session_start();
