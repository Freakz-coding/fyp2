<?php
// mark_read.php
$servername = "localhost";
$username = "beta_user"; // Replace with your username
$password = "mysql"; // Replace with your password
$dbname = "beta_project"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID is set in the GET request
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to int for security
    $sql = "UPDATE feedback SET status = 1 WHERE id = ?"; // Prepared statement for security

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind the ID parameter

    if ($stmt->execute()) {
        // Feedback marked as read successfully
        header('Location: support_management.php?message=Feedback marked as read successfully.');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Redirect back if no ID was provided
    header('Location: support_management.php?error=No feedback ID provided.');
    exit;
}

$stmt->close();
$conn->close();
?>
