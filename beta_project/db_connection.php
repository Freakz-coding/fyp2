<?php 
// db_connection.php

// Database connection variables
$servername = "localhost"; // or your server name
$username = "beta_user"; // your MySQL username
$password = "mysql"; // your MySQL password
$dbname = "beta_project"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8
$conn->set_charset("utf8");

// Function to get total users
function getTotalUsers($conn) {
    $query = "SELECT COUNT(*) AS total_users FROM users";
    $result = $conn->query($query);
    // Check for query success
    if (!$result) {
        return 0; // Return 0 if query fails
    }
    $data = $result->fetch_assoc();
    return $data['total_users'];
}

// Function to get total expenses for the current month
function getTotalExpensesThisMonth($conn, $userId) {
    $query = "SELECT SUM(amount) AS total FROM expenses WHERE MONTH(expense_date) = MONTH(CURRENT_DATE()) AND user_id = $userId";
    $result = $conn->query($query);
    if (!$result) {
        return 0; // Return 0 if query fails
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0; // Return 0 if no expenses found
}

// Function to get total expenses for the current year
function getTotalExpensesThisYear($conn, $userId) {
    $query = "SELECT SUM(amount) AS total FROM expenses WHERE YEAR(expense_date) = YEAR(CURRENT_DATE()) AND user_id = $userId";
    $result = $conn->query($query);
    if (!$result) {
        return 0; // Return 0 if query fails
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0; // Return 0 if no expenses found
}

// Function to get recent transactions
function getRecentTransactions($conn, $userId) {
    $query = "SELECT * FROM expenses WHERE user_id = $userId ORDER BY expense_date DESC LIMIT 5"; // Adjust as needed
    $result = $conn->query($query);
    $transactions = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    return $transactions;
}

// Function to get monthly expenses by category
function getMonthlyExpensesByCategory($conn, $userId) {
    $query = "SELECT MONTH(expense_date) AS month, category, SUM(amount) AS total FROM expenses WHERE user_id = $userId GROUP BY month, category";
    $result = $conn->query($query);
    $expensesByCategory = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $month = $row['month'];
            $category = $row['category'];
            $expensesByCategory[$month][$category] = $row['total'];
        }
    }
    return $expensesByCategory;
}

// Function to get spending insights
function getSpendingInsights($conn, $userId) {
    $insights = [];

    // Get total number of transactions
    $sql = "SELECT COUNT(*) AS total_transactions FROM expenses WHERE user_id = $userId";
    $result = $conn->query($sql);
    $totalTransactions = $result ? $result->fetch_assoc()['total_transactions'] : 0;
    $insights[] = "You have made a total of $totalTransactions transactions.";

    // Get average spending per transaction
    $sql = "SELECT AVG(amount) AS average_spending FROM expenses WHERE user_id = $userId";
    $result = $conn->query($sql);
    $averageSpending = $result ? $result->fetch_assoc()['average_spending'] : 0;
    $insights[] = "Your average spending per transaction is RM " . number_format($averageSpending, 2) . ".";

    // Get spending by category
    $sql = "SELECT category, SUM(amount) AS total_spent FROM expenses WHERE user_id = $userId GROUP BY category";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $insights[] = "You spent RM " . number_format($row['total_spent'], 2) . " on " . htmlspecialchars($row['category']) . ".";
        }
    }

    return $insights; // Return an array of insights
}

// Function to get spending alerts by month
function getSpendingAlerts($conn, $userId) {
    $alerts = [];

    $query = "SELECT * FROM users WHERE id = $userId"; // Assuming the table is named 'users' and has 'user_id' and 'username' columns
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    $spendingThreshold = $row['limit_expense']; // Define your threshold for alerts

    // Query to check monthly spending by category and compare it to the threshold
    $sql = "SELECT MONTH(expense_date) AS month, category, SUM(amount) AS total_spent 
            FROM expenses 
            WHERE user_id = $userId
            GROUP BY month, category 
            HAVING total_spent > ?";
    
    // Using prepared statements to avoid SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $spendingThreshold); // Bind threshold as parameter
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result) {
            // Process each row to create a detailed alert
            while ($row = $result->fetch_assoc()) {
                $month = date("F", mktime(0, 0, 0, $row['month'], 1)); // Convert month number to month name
                $category = htmlspecialchars($row['category']);
                $totalSpent = number_format($row['total_spent'], 2);
                
                $alerts[] = "Alert: In $month, you have spent RM $totalSpent on $category, exceeding your limit of RM $spendingThreshold.";
            }
        }
    }

    return $alerts; // Return an array of alerts
}

// function for admin


// Function to get total expenses for the current month
function getTotalExpensesThisMonthAdmin($conn) {
    $query = "SELECT SUM(amount) AS total FROM expenses WHERE MONTH(expense_date) = MONTH(CURRENT_DATE())";
    $result = $conn->query($query);
    if (!$result) {
        return 0; // Return 0 if query fails
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0; // Return 0 if no expenses found
}

// Function to get total expenses for the current year
function getTotalExpensesThisYearAdmin($conn) {
    $query = "SELECT SUM(amount) AS total FROM expenses WHERE YEAR(expense_date) = YEAR(CURRENT_DATE())";
    $result = $conn->query($query);
    if (!$result) {
        return 0; // Return 0 if query fails
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0; // Return 0 if no expenses found
}

// Function to get recent transactions
function getRecentTransactionsAdmin($conn) {
    $query = "SELECT * FROM expenses ORDER BY expense_date DESC LIMIT 5"; // Adjust as needed
    $result = $conn->query($query);
    $transactions = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    return $transactions;
}

// Function to get monthly expenses by category
function getMonthlyExpensesByCategoryAdmin($conn) {
    $query = "SELECT MONTH(expense_date) AS month, category, SUM(amount) AS total FROM expenses GROUP BY month, category";
    $result = $conn->query($query);
    $expensesByCategory = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $month = $row['month'];
            $category = $row['category'];
            $expensesByCategory[$month][$category] = $row['total'];
        }
    }
    return $expensesByCategory;
}







// User Management Functions
function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : []; // Return all users or empty array if error
}

function addUser($conn, $username, $email, $password, $role) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        $stmt->execute();
        $stmt->close();
    }
}

function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    return null; // Return null if the statement fails
}

function updateUser($conn, $userId, $username, $email, $role) {
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sssi", $username, $email, $role, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

function deleteUser($conn, $userId) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}

function assignRole($conn, $userId, $role) {
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $role, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Function to get all expenses
function getAllExpenses($conn) {
    $query = "SELECT * FROM expenses"; // Adjust fields as necessary
    $result = $conn->query($query);
    
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : []; // Return all expenses or empty array if error
}

// Function to get system settings
function getSystemSettings($conn) {
    $settings = [];
    $query = "SELECT * FROM system_settings"; // Fetch from system_settings
    $result = $conn->query($query);

    // Check if query was successful
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            // Assuming you want to use just the first row of settings
            $settings['budget_limit'] = $row['budget_limit'];
            $settings['alert_threshold'] = $row['alert_threshold'];
        }
    } else {
        // Optionally log the error
        error_log("Error fetching system settings: " . $conn->error);
    }

    return $settings; // Return the settings array
}

?>
