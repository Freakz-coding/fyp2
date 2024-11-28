<?php
// Include your database connection file
include 'db_connection.php'; // Make sure to create this file to handle the connection

// Start session for feedback messages
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    // Input validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: add_user.php");
        exit();
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    // Execute the statement and handle success or error
    if ($stmt->execute()) {
        $_SESSION['success'] = "User added successfully.";
        header("Location: manage_users.php");
    } else {
        $_SESSION['error'] = "Error adding user: " . $stmt->error;
        header("Location: add_user.php");
    }
    
    $stmt->close();
}

$conn->close();
?>
