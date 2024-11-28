<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}else{
    $userId = $_SESSION['user_id'];
}

include('db_connection.php'); 

// Fetch all expenses
$query = "SELECT * FROM expenses WHERE user_id = $userId ORDER BY expense_date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
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
            background-color: #f4f4f4; /* Light background color */
            color: #333; /* Default text color */
        }

        /* Container for the table */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff; /* White background for container */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Centering the Form Heading */
        h2 {
            text-align: center; /* Centering the heading */
            color: #2196F3; /* Blue color for headings */
            margin-bottom: 20px; /* Space below the heading */
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2196F3; /* Blue for table header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #e3f2fd; /* Light blue for even rows */
        }

        /* Button Styles */
        .edit-btn, .delete-btn {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-btn {
            background-color: #1976D2; /* Darker blue for edit button */
            color: white;
        }

        .delete-btn {
            background-color: #f44336; /* Red for delete button */
            color: white;
        }

        .edit-btn:hover {
            background-color: #1565C0; /* Even darker blue on hover */
        }

        .delete-btn:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }

        /* Back to Dashboard link */
        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #2196F3; /* Blue color for links */
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Expenses</h2>
        <table>
            <thead>
                <tr>
                    <th>Expense Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($expense = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo date('Y-m-d', strtotime($expense['expense_date'])); ?></td>
                            <td><?php echo ucfirst($expense['category']); ?></td>
                            <td><?php echo number_format($expense['amount'], 2); ?></td>
                            <td><?php echo ucfirst($expense['added_by']); ?></td>
                            <td>
                                <a class="edit-btn" href="edit_expense.php?id=<?php echo $expense['id']; ?>">Edit</a>
                                <a class="delete-btn" href="delete_expense.php?id=<?php echo $expense['id']; ?>" onclick="return confirm('Are you sure you want to delete this expense?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No expenses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <a href="user_dashboard.php">Back to Dashboard</a>

    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
