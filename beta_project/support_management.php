<?php
// Database connection
$servername = "localhost"; // Change if necessary
$username = "beta_user"; // Update with your username
$password = "mysql"; // Update with your password
$dbname = "beta_project"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to include a WHERE clause if a search term is provided
$sql = "SELECT id, name, email, feedback, submitted_at, status FROM feedback";
if (!empty($searchTerm)) {
    $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR email LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}
$sql .= " ORDER BY submitted_at DESC";

$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8fafc;
            color: #1e40af;
        }
        .header {
            background-color: #1d4ed8;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .table {
            margin: 20px auto;
            max-width: 800px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Feedback Management</h1>
    </div>

    <div class="container">
        <div class="mb-3">
            <form method="GET" action="support_management.php">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search feedback by name or email" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Feedback</th>
                        <th>Submitted At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . htmlspecialchars($row["name"]) . "</td>
                                    <td>" . htmlspecialchars($row["email"]) . "</td>
                                    <td>" . htmlspecialchars($row["feedback"]) . "</td>
                                    <td>" . $row["submitted_at"] . "</td>
                                    <td>" . ($row["status"] == 1 ? 'Read' : 'New') . "</td>
                                    <td>
                                        <a href='mark_read.php?id=" . $row["id"] . "' class='btn btn-secondary btn-sm'>Mark as Read</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No feedback available</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <p class="mt-3"><a href="admin_dashboard.php">Home</a></p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
