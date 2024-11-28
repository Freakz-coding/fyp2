<?php
include 'db_connection.php'; // Include the database connection
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch expenses from the database
$expenses = getAllExpenses($conn); // This retrieves all expenses into $expenses
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Expenses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>All Expenses</h1>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($expenses)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No expenses found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?php echo date("d-m-Y", strtotime($expense['expense_date'])); ?></td>
                            <td><?php echo htmlspecialchars($expense['category']); ?></td>
                            <td>RM <?php echo number_format($expense['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars(isset($expense['username']) ? $expense['username'] : 'N/A'); ?></td>
                            <td>
                                <a href="edit_expense.php?id=<?php echo $expense['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_expense.php?id=<?php echo $expense['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>
</html>

<?php $conn->close(); // Close the database connection ?>
