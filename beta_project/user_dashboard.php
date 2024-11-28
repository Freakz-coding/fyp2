<?php 
session_start(); 
include 'db_connection.php'; 

$totalExpensesThisMonth = getTotalExpensesThisMonth($conn, $_SESSION['user_id']); 
$totalExpensesThisYear = getTotalExpensesThisYear($conn, $_SESSION['user_id']); 
$recentTransactions = getRecentTransactions($conn, $_SESSION['user_id']); 
$monthlyExpensesByCategory = getMonthlyExpensesByCategory($conn, $_SESSION['user_id']); 

// Get spending insights and alerts 
$spendingInsights = getSpendingInsights($conn, $_SESSION['user_id']); 
$spendingAlerts = getSpendingAlerts($conn, $_SESSION['user_id']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            background-color: #f1f5f9; color: #023e8a; 
        }
        
        .header { 
            background-color: #0077b6; color: white; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; 
        }

        .menu-bar { 
            display: flex; justify-content: center; gap: 20px; padding: 10px; background-color: #023e8a; border-radius: 5px; margin-bottom: 20px; 
        }

        .menu-bar a {
             color: white; font-weight: bold; text-decoration: none; padding: 8px 15px; border-radius: 4px; transition: background-color 0.3s ease; 
        }

        .menu-bar a:hover { 
            background-color: #0077b6; 
        }

        .summary { 
            display: flex; justify-content: space-around; margin-bottom: 20px; 
        }

        .summary-card { 
            background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); flex: 1; margin: 0 10px; 
        }

        .chart-container { 
            position: relative; width: 80%; margin: 0 auto; height: 400px; 
        }

        .legend-container { 
            position: absolute; top: 10px; right: 10px; background-color: white; border: 1px solid #e0e0e0; padding: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        }

        .legend-item { 
            display: flex; align-items: center; margin-bottom: 5px; 
        }

        .legend-color { 
            width: 15px; height: 15px; border-radius: 3px; margin-right: 5px; 
        }

        .table td, .table th { 
            text-align: center; 
        }

        .transactions-title { 
            background-color: #0077b6; color: white; padding: 10px 15px; text-align: center; border-radius: 5px; font-weight: bold; margin: 30px auto 15px; width: 50%; 
        }

        .alert-section { 
            margin: 20px 0; 
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>Expense Dashboard</h1>
        <p>Track monthly and yearly expenses effectively</p>
    </div>
    <div class="menu-bar">
        <a href="add_expense.php">Add Expense</a>
        <a href="view_expenses.php">View Expenses</a>
        <a href="generate_report.php">Generate Reports</a>
        <a href="support_request.php">Support Request</a> <!-- New Link -->
     
        <a href="settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="summary">
        <div class="summary-card text-center">
            <h5>Total Expenses This Month</h5>
            <h2>RM <?php echo number_format($totalExpensesThisMonth, 2); ?></h2>
        </div>
        <div class="summary-card text-center">
            <h5>Total Expenses This Year</h5>
            <h2>RM <?php echo number_format($totalExpensesThisYear, 2); ?></h2>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="expensesChart"></canvas>
        <div class="legend-container" id="legend"></div>
    </div>
    <div class="chart-container">
        <canvas id="monthlyExpensesLineChart"></canvas>
    </div>
    <h2 class="transactions-title">Recent Transactions</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Added By</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentTransactions as $transaction): ?>
                <tr>
                    <td><?php echo date("d-m-Y", strtotime($transaction['expense_date'])); ?></td>
                    <td><?php echo htmlspecialchars($transaction['category']); ?></td>
                    <td>RM <?php echo number_format($transaction['amount'], 2); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($transaction['added_by'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="alert-section">
        <h3>Real-Time Alerts/Notifications</h3>
        <ul class="list-group">
            <?php foreach ($spendingAlerts as $alert): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($alert); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="alert-section">
        <h3>Spending Insights</h3>
        <ul class="list-group">
            <?php foreach ($spendingInsights as $insight): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($insight); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
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

        // Line Chart for Monthly Expenses by Category
        const lineChartCtx = document.getElementById('monthlyExpensesLineChart').getContext('2d');
        const monthlyExpensesData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                <?php foreach ($categories as $category => $data): ?>
                    {
                        label: '<?php echo htmlspecialchars($category); ?>',
                        data: [<?php echo implode(',', $data); ?>],
                        borderColor: '<?php echo array_shift($colors); ?>',
                        fill: false,
                    },
                <?php endforeach; ?>
            ]
        };

        const monthlyExpensesLineChart = new Chart(lineChartCtx, {
            type: 'line',
            data: monthlyExpensesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
