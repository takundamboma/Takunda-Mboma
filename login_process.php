<?php
// Include database connection file
include 'config.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL statement to retrieve user information
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Verify password
    if (password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        // Redirect to the dashboard or homepage
        header("Location: index.html");
        exit();
    } else {
        // Invalid password
        echo "Invalid password";
    }
} else {
    // User not found
    echo "User not found";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>