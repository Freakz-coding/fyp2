<?php
session_start();
// Include database connection
include 'db_connection.php';

// Handle deletion of an expense
if (isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $expense_id);
        $stmt->execute();
        $stmt->close();
        if($_SESSION['role'] === 'user'){

            header("Location: view_expenses.php"); // Redirect after updating
        }else if($_SESSION['role'] == 'admin'){
            header("Location: expense_management.php"); // Redirect after updating
        }// Redirect to avoid reloading the page
        exit();
    }
} else {
    header("Location: user_dashboard.php"); // Redirect if no ID is provided
    exit();
}
