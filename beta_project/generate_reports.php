<?php
include 'db_connection.php';
session_start();

// Implement report generation logic
$report = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];

    switch ($reportType) {
        case 'monthly_expenses':
            $query = "SELECT YEAR(expense_date) AS year, MONTH(expense_date) AS month, SUM(amount) AS total_monthly_expenses 
                      FROM expenses 
                      GROUP BY year, month 
                      ORDER BY year, month";
            $result = $conn->query($query);
            $report = $result->fetch_all(MYSQLI_ASSOC);
            $grandTotals = 0;
            foreach ($report as $row) {
                $grandTotals += $row['total_monthly_expenses'];
            }
            break;

        case 'user_expenses':
            $query = "SELECT user_id, SUM(amount) AS total_user_expenses 
                      FROM expenses 
                      GROUP BY user_id";
            $result = $conn->query($query);
            $report = $result->fetch_all(MYSQLI_ASSOC);
            break;

        case 'category_expenses':
            $query = "SELECT category, SUM(amount) AS total_category_expenses 
                      FROM expenses 
                      GROUP BY category";
            $result = $conn->query($query);
            $report = $result->fetch_all(MYSQLI_ASSOC);
            $grandTotal = 0;
            foreach ($report as $row) {
                $grandTotal += $row['total_category_expenses'];
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #007bff;
        }
        .btn {
            font-weight: 600;
            padding: 10px 20px;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .form-select, .form-label {
            font-size: 1rem;
        }
        .report-table {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1 class="card-title">Generate Reports</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="reportType" class="form-label">Select Report Type</label>
                <select name="reportType" id="reportType" class="form-select" required>
                    <option value="monthly_expenses">Monthly Expenses</option>
                    <option value="category_expenses">Category Expenses</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    </div>

    <?php if ($report): ?>
        <div class="card report-table">
            <h2 class="mt-4 mb-3">Report Results</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <?php if ($reportType == 'monthly_expenses'): ?>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Total Expenses (RM)</th>
                        <?php elseif ($reportType == 'user_expenses'): ?>
                            <th>User ID</th>
                            <th>Total Expenses (RM)</th>
                        <?php elseif ($reportType == 'category_expenses'): ?>
                            <th>Category</th>
                            <th>Total Expenses (RM)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $row): ?>
                        <tr>
                            <?php if ($reportType == 'monthly_expenses'): ?>
                                <td><?php echo $row['year']; ?></td>
                                <td><?php echo $row['month']; ?></td>
                                <td>RM <?php echo number_format($row['total_monthly_expenses'], 2); ?></td>
                            <?php elseif ($reportType == 'user_expenses'): ?>
                                <td><?php echo $row['user_id']; ?></td>
                                <td>RM <?php echo number_format($row['total_user_expenses'], 2); ?></td>
                            <?php elseif ($reportType == 'category_expenses'): ?>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td>RM <?php echo number_format($row['total_category_expenses'], 2); ?></td>
                                
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if ($reportType == 'category_expenses'): ?>
                <tfoot>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong>RM <?php echo number_format($grandTotal, 2); ?></strong></td>
                    </tr>
                </tfoot>
                <?php elseif ($reportType == 'monthly_expenses'): ?>
                <tfoot>
                    <tr>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td><strong>RM <?php echo number_format($grandTotals, 2); ?></strong></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>

            <!-- Button to Download as CSV -->
            <form action="export_csv.php" method="POST" class="mt-3">
                <input type="hidden" name="reportType" value="<?php echo $reportType; ?>">
                <button type="submit" class="btn btn-success">Download as CSV</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
