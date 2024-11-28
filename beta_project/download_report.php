<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle report download
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    
    switch ($reportType) {
        case 'monthly_expenses':
            // Fetch data for monthly expenses as shown earlier
            $query = "SELECT YEAR(expense_date) AS year, MONTH(expense_date) AS month, SUM(amount) AS total_monthly_expenses 
                      FROM expenses 
                      GROUP BY year, month 
                      ORDER BY year, month";
            break;

        case 'user_expenses':
            $query = "SELECT user_id, SUM(amount) AS total_user_expenses 
                      FROM expenses 
                      GROUP BY user_id";
            break;

        case 'category_expenses':
            $query = "SELECT category, SUM(amount) AS total_category_expenses 
                      FROM expenses 
                      GROUP BY category";
            break;
    }

    $result = $conn->query($query);
    
    // Prepare for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');

    $output = fopen('php://output', 'w');

    // Add column headers based on report type
    if ($reportType == 'monthly_expenses') {
        fputcsv($output, ['Year', 'Month', 'Total Expenses']);
    } elseif ($reportType == 'user_expenses') {
        fputcsv($output, ['User ID', 'Total Expenses']);
    } elseif ($reportType == 'category_expenses') {
        fputcsv($output, ['Category', 'Total Expenses']);
    }

    // Fetch and output each row of data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

mysqli_close($conn);
?>
