<?php
include 'db_connection.php';
session_start();

// Fetch all users from the database
$query = "SELECT * FROM users"; // Assuming the table is named 'users' and has 'user_id' and 'username' columns
$result = mysqli_query($conn, $query);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected user, category, month, and expense limit from the form
    $userId = $_POST['user_id'];  // This will now be the selected user ID from the dropdown
    $expenseLimit = $_POST['expense_limit'];
    

    // Insert or update the limit in the user_expense_limits table
     $stmt = $conn->prepare("UPDATE users SET limit_expense = ? WHERE id = $userId");
    if ($stmt) {
        $stmt->bind_param("s", $expenseLimit);
        $stmt->execute();
        $stmt->close();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Monthly Expense Limit by Category</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2 class="mt-4">Set Monthly Expense Limit</h2>
    <form method="POST" action="">
        <!-- User Dropdown -->
        <div class="mb-3">
            <label for="user_id" class="form-label">Select User</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <option value="">Select User</option>
                <?php
                // Loop through the results to populate the user dropdown
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['id']}'>{$row['username']}</option>";
                }
                ?>
            </select>
        </div>


        <!-- Expense Limit Input -->
        <div class="mb-3">
            <label for="expense_limit" class="form-label">Expense Limit (RM)</label>
            <input type="number" step="0.01" class="form-control" id="expense_limit" name="expense_limit" required>
        </div>

        <button type="submit" class="btn btn-primary">Set Limit</button>
    </form>

    <!-- Back Button -->
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script>
    // Show or hide the custom category input field based on 'Others' selection
    document.getElementById('category').addEventListener('change', function() {
        var otherCategoryDiv = document.getElementById('otherCategoryDiv');
        if (this.value === 'Others') {
            otherCategoryDiv.style.display = 'block';
        } else {
            otherCategoryDiv.style.display = 'none';
        }
    });
</script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>