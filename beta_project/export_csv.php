<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    $filename = "report_" . $reportType . "_" . date("Y-m-d") . ".csv";
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $filename);

    $output = fopen('php://output', 'w');

    // Define headers based on report type
    if ($reportType === 'monthly_expenses') {
        fputcsv($output, ['Year', 'Month', 'Total Expenses (RM)']);
        $query = "SELECT YEAR(expense_date) AS year, MONTH(expense_date) AS month, SUM(amount) AS total_monthly_expenses 
                  FROM expenses 
                  GROUP BY year, month 
                  ORDER BY year, month";
    } elseif ($reportType === 'user_expenses') {
        fputcsv($output, ['User ID', 'Total Expenses (RM)']);
        $query = "SELECT user_id, SUM(amount) AS total_user_expenses 
                  FROM expenses 
                  GROUP BY user_id";
    } elseif ($reportType === 'category_expenses') {
        fputcsv($output, ['Category', 'Total Expenses (RM)']);
        $query = "SELECT category, SUM(amount) AS total_category_expenses 
                  FROM expenses 
                  GROUP BY category";
    }

    $result = $conn->query($query);
    $grandTotal = 0.0;

    // Output the data rows
    while ($row = $result->fetch_assoc()) {
        // Add the appropriate grand total calculation based on the report type
        if ($reportType === 'monthly_expenses') {
            $grandTotal += $row['total_monthly_expenses'];
        } elseif ($reportType === 'user_expenses') {
            $grandTotal += $row['total_user_expenses'];
        } elseif ($reportType === 'category_expenses') {
            $grandTotal += $row['total_category_expenses'];
        }
        
        fputcsv($output, $row);
    }

    if ($reportType === 'category_expenses') {
           // Output the grand total as a row
    fputcsv($output, ['TOTAL', $grandTotal]);
    }else{

        // Output the grand total as a row
        fputcsv($output, ['', 'TOTAL', $grandTotal]);
    }


    fclose($output);
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
