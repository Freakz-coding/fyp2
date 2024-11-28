<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}else{
    $userId = $_SESSION['user_id'];
}

// Include the updated database connection file
include('db_connection.php'); // Update to use db_connection.php

// Initialize variables
$start_date = '';
$end_date = '';
$expenses = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch expenses based on the date range
    $query = "SELECT * FROM expenses WHERE expense_date BETWEEN '$start_date' AND '$end_date' AND user_id = $userId ORDER BY expense_date ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $expenses[] = $row;
        }
    } else {
        echo "Error fetching expenses: " . mysqli_error($conn);
    }
}

// Function to download CSV
function download_csv($expenses) {
    // Set headers for the CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="expenses_report.csv"');

    $output = fopen('php://output', 'w');

    // Add CSV column headers
    fputcsv($output, ['Expense Date', 'Category', 'Amount (RM)', 'Added By']);

    // Add rows to CSV
    foreach ($expenses as $expense) {
        fputcsv($output, [
            $expense['expense_date'], 
            $expense['category'], 
            'RM ' . number_format($expense['amount'], 2), // Format amount as RM
            ucwords($expense['added_by']) // Capitalize initials for CSV
        ]);
    }

    fclose($output);
    exit();
}

// Handle CSV download request
if (isset($_GET['download'])) {
    // Get start and end dates from the URL
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    // Fetch expenses again based on the date range for CSV
    $query = "SELECT * FROM expenses WHERE expense_date BETWEEN '$start_date' AND '$end_date' AND user_id = $userId ORDER BY expense_date";
    $result = mysqli_query($conn, $query);

    $expenses = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $expenses[] = $row;
        }
    }

    // If there are expenses, download CSV; otherwise, show message
    if (!empty($expenses)) {
        download_csv($expenses);
    } else {
        echo "No expenses found for the selected date range.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff; /* Light blue background */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container for the form */
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        /* Form Heading */
        h2 {
            text-align: center;
            color: #0056b3; /* Dark blue color */
            margin-bottom: 20px;
        }

        /* Form Labels */
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #0056b3; /* Dark blue color */
        }

        /* Input Fields */
        form input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Button Styles */
        button {
            width: 100%;
            padding: 12px;
            background-color: #0056b3; /* Dark blue color */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #004494; /* Slightly darker blue */
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 10px; /* Adjusted to reduce space */
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center; /* Centered text in table cells */
        }

        th {
            background-color: #e0e0e0; /* Light grey for headers */
            color: #0056b3; /* Dark blue color */
        }

        /* Additional spacing for the expenses message */
        .expenses-message {
            margin-top: 20px; /* Add margin for space */
        }

        /* Back to Dashboard and Download CSV links */
        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #0056b3; /* Dark blue color */
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Bottom right positioning for the back link */
        .link-container {
            display: flex;
            justify-content: space-between; /* Space out links */
            margin-top: 20px; /* Margin for space */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Generate Expense Report</h2>
        <form method="POST" action="generate_report.php">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>

            <button type="submit">Generate Report</button>
        </form>

        <?php if (!empty($expenses)): ?>
            <h3 class="expenses-message">Expenses from <?php echo htmlspecialchars($start_date); ?> to <?php echo htmlspecialchars($end_date); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Expense Date</th>
                        <th>Category</th>
                        <th>Amount (RM)</th>
                        <th>Added By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                            <td><?php echo htmlspecialchars($expense['category']); ?></td>
                            <td>RM <?php echo number_format($expense['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars(ucwords($expense['added_by'])); ?></td> <!-- Capitalizing initials -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="link-container">
                <a href="user_dashboard.php">Back to Dashboard</a> <!-- Link to dashboard -->
                <a href="generate_report.php?download=true&start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>">Download CSV</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
