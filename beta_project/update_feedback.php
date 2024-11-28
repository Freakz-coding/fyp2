<?php
session_start();
include 'db_connection.php';

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Update feedback status
if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = $_GET['id'];
    $status = $_GET['status'];
    

    if (in_array($status, ['in-progress', 'resolved'])) {
        $stmt = $conn->prepare("UPDATE support_requests SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: admin_feedback.php");
exit();
