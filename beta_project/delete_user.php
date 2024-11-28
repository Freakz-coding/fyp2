<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Delete user
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully.";
        header("Location: user_management.php");
    } else {
        $_SESSION['error'] = "Error deleting user: " . $stmt->error;
        header("Location: user_management.php");
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "User ID is not specified.";
    header("Location: user_management.php");
}

$conn->close();
?>
