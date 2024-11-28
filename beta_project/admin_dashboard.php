<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and is an admin

// Fetch data for the dashboard
$totalUsers = getTotalUsers($conn);
$totalExpensesThisMonth = getTotalExpensesThisMonthAdmin($conn);
$totalExpensesThisYear = getTotalExpensesThisYearAdmin($conn);
$recentTransactions = getRecentTransactionsAdmin($conn);
$monthlyExpensesByCategory = getMonthlyExpensesByCategoryAdmin($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
            text-align: center; /* Center all text in the body */
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .menu-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            background-color: #343a40;
            border-radius: 5px;
            padding: 10px;
            justify-content: center; /* Center the menu items */
        }
        .menu-bar a {
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .menu-bar a:hover {
            background-color: #007bff;
        }
        .summary {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            margin: 0 10px;
            text-align: center;
            transition: transform 0.2s;
        }
        .summary-card:hover {
            transform: scale(1.05);
        }
        .chart-container {
            max-width: 800px;
            margin: 20px auto;
        }
        .transactions-title {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
            margin: 30px auto 15px;
            width: fit-content;
        }
        .alert-section {
            margin: 20px 0;
        }
        .admin-section {
            margin-top: 30px;
        }
        h4 {
            color: #007bff;
        }
        @media (max-width: 768px) {
            .summary {
                flex-direction: column;
            }
            .summary-card {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Admin Expense Dashboard</h1>
        <p>Manage users and track overall expenses</p>
    </div>

    <div class="menu-bar">
        <a href="manage_users.php" title="Manage user accounts">Manage Users</a>
        <a href="expense_management.php" title="Manage all expenses">Manage Expenses</a>
        <a href="generate_reports.php" title="Generate financial reports">Generate Reports</a>
        <a href="admin_feedback.php" title="Manage user feedback">Manage Feedback</a>
        <a href="admin_limit.php" title="Adjust limit expense">Expenses Settings</a>
        <a href="logout.php" title="Log out of the dashboard">Logout</a>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h5>Total Users</h5>
            <h2><?php echo $totalUsers; ?></h2>
        </div>
        <div class="summary-card">
            <h5>Total Expenses This Month</h5>
            <h2>RM <?php echo number_format($totalExpensesThisMonth, 2); ?></h2>
        </div>
        <div class="summary-card">
            <h5>Total Expenses This Year</h5>
            <h2>RM <?php echo number_format($totalExpensesThisYear, 2); ?></h2>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="expensesChart"></canvas>
    </div>

    <h2 class="transactions-title">Recent Transactions</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentTransactions as $transaction): ?>
                <tr>
                    <td><?php echo date("d-m-Y", strtotime($transaction['expense_date'])); ?></td>
                    <td><?php echo ucwords(htmlspecialchars($transaction['category'])); ?></td>
                    <td>RM <?php echo number_format($transaction['amount'], 2); ?></td>
                    <td><?php echo isset($transaction['added_by']) ? ucwords(htmlspecialchars($transaction['added_by'])) : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        const ctx = document.getElementById('expensesChart').getContext('2d');
        const chartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                <?php 
                $categories = []; 
                $colors = ['#90e0ef', '#0077b6', '#023e8a', '#caf0f8', '#00b4d8', '#48cae4', '#0077b6', '#023e8a', '#00b4d8', '#90e0ef']; 
                foreach ($monthlyExpensesByCategory as $month => $categoriesData) {
                    foreach ($categoriesData as $category => $total) {
                        if (!isset($categories[$category])) {
                            $categories[$category] = array_fill(0, 12, 0);
                        }
                        $categories[$category][$month - 1] = $total;
                    }
                }
                foreach ($categories as $category => $data): 
                ?>
                    {
                        label: '<?php echo htmlspecialchars($category); ?>',
                        data: [<?php echo implode(',', $data); ?>],
                        backgroundColor: '<?php echo array_shift($colors); ?>',
                        borderWidth: 1,
                        stack: 'stack1'
                    },
                <?php endforeach; ?>
            ]
        };

        const expensesChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const legendContainer = document.getElementById('legend');
        chartData.datasets.forEach((dataset) => {
            const legendItem = document.createElement('div');
            legendItem.className = 'legend-item';
            legendItem.innerHTML = `<div class="legend-color" style="background-color: ${dataset.backgroundColor};"></div> ${dataset.label}`;
            legendContainer.appendChild(legendItem);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
