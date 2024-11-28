<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your DB username
$password = ""; // Your DB password
$dbname = "expense_management"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$overall_budget = $_POST['overall_budget'];
$category_budget = $_POST['category_budget'];
$alert_threshold = $_POST['alert_threshold'];
$timezone = $_POST['timezone'];
$currency = $_POST['currency'];
$fiscal_start = $_POST['fiscal_start'];
$fiscal_end = $_POST['fiscal_end'];

// Save settings to the database
$sql = "UPDATE system_settings SET 
        overall_budget = '$overall_budget', 
        category_budget = '$category_budget', 
        alert_threshold = '$alert_threshold', 
        timezone = '$timezone', 
        currency = '$currency', 
        fiscal_start = '$fiscal_start', 
        fiscal_end = '$fiscal_end' 
        WHERE id = 1"; // Assuming a single record for settings

if ($conn->query($sql) === TRUE) {
    echo "Settings saved successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
