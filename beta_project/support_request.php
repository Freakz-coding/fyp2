<?php
// support_request.php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$messageStatus = "";  // Message to show after submission
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    // User submits a new support request
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO support_requests (user_id, subject, message) VALUES ('$user_id', '$subject', '$message')";
    
    if ($conn->query($sql) === TRUE) {
        $messageStatus = "<div class='alert alert-success mt-3'>Support request submitted successfully!</div>";
    } else {
        $messageStatus = "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_reply'])) {
    // Admin replies to a support request
    $request_id = $_POST['request_id'];
    $admin_id = $_SESSION['user_id']; // Assuming admin is logged in
    $reply = $conn->real_escape_string($_POST['reply']);
    
    $sql = "INSERT INTO support_replies (support_request_id, admin_id, reply) VALUES ('$request_id', '$admin_id', '$reply')";
    
    if ($conn->query($sql) === TRUE) {
        $messageStatus = "<div class='alert alert-success mt-3'>Reply sent successfully!</div>";
    } else {
        $messageStatus = "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
    }
}

// Fetch the user's support requests and their replies
$sql_requests = "SELECT * FROM support_requests WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result_requests = $conn->query($sql_requests);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Support Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            color: #023e8a;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
            color: #0077b6;
        }
        .form-control:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 0.2rem rgba(0, 180, 216, 0.25);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #0077b6;
        }
        .btn-primary {
            background-color: #0077b6;
            border-color: #0077b6;
        }
        .btn-primary:hover {
            background-color: #005f8c;
            border-color: #005f8c;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Submit Support Request</h2>
        <p class="text-muted">Need assistance? Fill out the form below, and we’ll get back to you.</p>
    </div>

    <form method="POST">
        <div class="mb-3">
            <label for="subject" class="form-label"><i class="fas fa-tag"></i> Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter the subject" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label"><i class="fas fa-comments"></i> Message</label>
            <textarea name="message" id="message" class="form-control" placeholder="Describe your issue here" rows="5" required></textarea>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" name="submit_request" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Request</button>
            <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <?php echo $messageStatus; ?>

    <h3 class="mt-4">Your Support Requests</h3>
    <ul class="list-group">
        <?php while ($row = $result_requests->fetch_assoc()) { ?>
            <li class="list-group-item">
                <h5><?php echo htmlspecialchars($row['subject']); ?></h5>
                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                <p><strong>Submitted on:</strong> <?php echo $row['created_at']; ?></p>

                <!-- Display admin replies -->
                <?php
                $request_id = $row['id'];
                $sql_replies = "SELECT * FROM support_replies WHERE support_request_id = '$request_id' ORDER BY created_at DESC";
                $result_replies = $conn->query($sql_replies);
                while ($reply = $result_replies->fetch_assoc()) {
                    echo "<div class='border-top mt-3'>";
                    echo "<p><strong>Admin Reply:</strong></p>";
                    echo "<p>" . nl2br(htmlspecialchars($reply['message'])) . "</p>";
                    echo "<p><small>Replied on: " . $reply['created_at'] . "</small></p>";
                    echo "</div>";
                }
                ?>

                <!-- Admin reply form (if the admin is logged in) -->
                <?php if (isset($_SESSION['admin_id'])) { ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="reply" class="form-label"><i class="fas fa-comments"></i> Admin Reply</label>
                            <textarea name="reply" id="reply" class="form-control" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="submit_reply" class="btn btn-secondary">Submit Reply</button>
                    </form>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
