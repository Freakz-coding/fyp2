<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Input validation
    if (empty($username) || empty($email) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: edit_user.php?id=$id");
        exit();
    }

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully.";
        header("Location: user_management.php");
    } else {
        $_SESSION['error'] = "Error updating user: " . $stmt->error;
        header("Location: edit_user.php?id=$id");
    }
    $stmt->close();
}

$conn->close();
?>
