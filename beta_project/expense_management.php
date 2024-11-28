<?php
// Include database connection
include 'db_connection.php';

// Initialize an empty array for expenses
$expenses = [];

// Fetch expenses from the database
$result = $conn->query("SELECT * FROM expenses");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f9; /* Light gray background color for the page */
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-top: 20px;
            font-size: 2.5rem;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
            font-size: 14px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #d1e7dd;
        }

        td a {
            color: white;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 5px;
            background-color: #28a745;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        td a.delete {
            background-color: #dc3545;
        }

        td a:hover {
            background-color: #218838;
        }

        td a.delete:hover {
            background-color: #c82333;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container a {
            background-color: #007bff;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button-container a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }

            table {
                font-size: 12px;
            }

            td a {
                font-size: 12px;
                padding: 5px 8px;
            }
        }
    </style>
</head>
<body>

<h1>Expense Management</h1>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Expense Date</th>
                <th>Category</th>
                <th>Added By</th>
                <th>User ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?php echo htmlspecialchars($expense['id']); ?></td>
                    <td><?php echo htmlspecialchars($expense['amount']); ?></td>
                    <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                    <td><?php echo htmlspecialchars($expense['category']); ?></td>
                    <td><?php echo htmlspecialchars($expense['added_by']); ?></td>
                    <td><?php echo htmlspecialchars($expense['user_id']); ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?php echo $expense['id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete_expense.php?id=<?php echo $expense['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this expense?');"><i class="fas fa-trash-alt"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="button-container">
    <a href="admin_dashboard.php" role="button" aria-label="View Dashboard">View Dashboard</a>
</div>

</body>
</html>
