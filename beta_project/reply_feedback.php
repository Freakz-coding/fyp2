<?php
session_start();
include 'db_connection.php';

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the ID is set in the query string
if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

// Get the support request ID from the URL
$request_id = $_GET['id'];

// Fetch the support request details from the database
$query = "SELECT sf.id, u.username, sf.subject, sf.message, sf.status, sf.created_at 
          FROM support_requests sf 
          JOIN users u ON sf.user_id = u.id 
          WHERE sf.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Support request not found.";
    exit();
}

$request = $result->fetch_assoc();

// Handle the reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_message = $_POST['reply_message'];
    
    if (!empty($reply_message)) {
        // Insert the reply into the database (assuming a replies table exists)
        $insert_query = "INSERT INTO support_replies (support_request_id, admin_id, message, created_at) 
                         VALUES (?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iis", $request_id, $_SESSION['user_id'], $reply_message);
        $insert_stmt->execute();

        // Update the support request status to 'resolved'
        $update_query = "UPDATE support_requests SET status = 'resolved' WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $request_id);
        $update_stmt->execute();

        // Redirect back to the admin dashboard or support requests page
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Reply message cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Support Request</title>
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
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Main Container */
        .container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
            font-size: 2rem;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        textarea {
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        /* Back Button */
        .btn-back {
            background-color: #FF5722;
            margin-top: 20px;
            text-align: center;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Reply to Support Request</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <p><strong>User:</strong> <?php echo htmlspecialchars($request['username']); ?></p>
        <p><strong>Subject:</strong> <?php echo htmlspecialchars($request['subject']); ?></p>
        <p><strong>Message:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($request['message'])); ?></p>

        <form action="reply_feedback.php?id=<?php echo $request['id']; ?>" method="POST">
            <textarea name="reply_message" rows="6" placeholder="Write your reply here..."></textarea>
            <button type="submit">Submit Reply</button>
        </form>

        <a href="admin_dashboard.php" class="btn-back">Go Back to Dashboard</a>
    </div>

</body>
</html>
