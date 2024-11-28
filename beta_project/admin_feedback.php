<?php
session_start();
include 'db_connection.php';

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all feedback entries in ascending order (by submitted_at)
$result = $conn->query("SELECT sf.id, u.username, sf.subject, sf.message, sf.status, sf.created_at 
                        FROM support_requests sf 
                        JOIN users u ON sf.user_id = u.id 
                        ORDER BY sf.created_at ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #f7f7f7;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* Main Container */
        .container {
            width: 100%;
            max-width: 1200px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        /* Header */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4CAF50;
            font-size: 2rem;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-weight: 500;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            margin-right: 10px;
        }

        table td a:hover {
            text-decoration: underline;
        }

        /* Status Labels */
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            color: black;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status.pending {
            background-color: #FF9800;
        }

        .status.in-progress {
            background-color: #FF9800;
        }

        .status.resolved {
            background-color: #66BB6A;
        }

        /* Button Styling */
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block; /* Ensure it doesn't overflow */
            white-space: nowrap; /* Prevents button text from breaking into a new line */
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-back {
            background-color: #FF5722;
            position: fixed; /* Fixed to the bottom-left corner */
            left: 20px;
            bottom: 20px;
            z-index: 100;
            display: inline-block; /* Ensure it doesn't overlap */
            white-space: nowrap;
        }

        .btn-back:hover {
            background-color: #e64a19;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table, th, td {
                display: block;
                width: 100%;
            }
            
            table th {
                text-align: left;
            }
            
            table tr {
                margin-bottom: 15px;
            }
            
            table td {
                padding: 10px 5px;
            }
            
            .btn-back {
                bottom: 10px; /* Adjust position on smaller screens */
                left: 10px; /* Adjust position on smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Support Requests</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><span class="status <?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="update_feedback.php?id=<?php echo $row['id']; ?>&status=resolved" class="btn">Resolve</a>
                        <a href="reply_feedback.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color: #FF9800;">Reply</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Go Back to Dashboard Button -->
    <a href="admin_dashboard.php" class="btn btn-back">Go Back to Dashboard</a>
</body>
</html>
