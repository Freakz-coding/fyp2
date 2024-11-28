<?php
session_start();
// Include database connection
include 'db_connection.php';

// Fetch the expense details for the given ID
if (isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM expenses WHERE id = ?");
    $stmt->bind_param("i", $expense_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $expense = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: user_dashboard.php"); // Redirect if no ID is provided
    exit();
}

// Handle form submission for updating the expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_expense'])) {
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $category = $_POST['category'];
    $added_by = $_POST['added_by']; // This could be updated based on user action
    $user_id = $_POST['user_id']; // This could also be set based on user session

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE expenses SET amount = ?, expense_date = ?, category = ?, added_by = ?, user_id = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("dsssii", $amount, $expense_date, $category, $added_by, $user_id, $expense_id);
        $stmt->execute();
        $stmt->close();
        if($_SESSION['role'] === 'user'){

            header("Location: view_expenses.php"); // Redirect after updating
        }else if($_SESSION['role'] == 'admin'){
            header("Location: expense_management.php"); // Redirect after updating
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa; /* Light background for contrast */
        }

        h1 {
            text-align: center;
            color: #343a40; /* Darker text for better readability */
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: bold;
            color: #495057; /* Darker color for labels */
        }

        input[type="number"],
        input[type="date"],
        input[type="text"] {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="text"]:focus {
            border-color: #80bdff;
            outline: none;
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Expense</h1>

    <!-- Form to edit an existing expense -->
    <form method="POST" action="">
        <label for="amount">Amount:</label>
        <input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($expense['amount']); ?>" required>

        <label for="expense_date">Expense Date:</label>
        <input type="date" name="expense_date" value="<?php echo htmlspecialchars($expense['expense_date']); ?>" required>

        <label for="category">Category:</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($expense['category']); ?>" required>

        <input type="hidden" name="added_by" value="<?php echo htmlspecialchars($expense['added_by']); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($expense['user_id']); ?>">
        
        <button type="submit" name="update_expense">Update Expense</button>
    </form>
</div>

</body>
</html>
