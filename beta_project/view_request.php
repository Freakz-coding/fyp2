<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Support Request Details</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .container {
        width: 100%;
        max-width: 600px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .header h1 {
        font-size: 28px;
        color: #333;
        border-bottom: 2px solid #007bff;
        display: inline-block;
        padding-bottom: 8px;
    }
    .details p {
        margin: 15px 0;
        font-size: 16px;
    }
    .details p strong {
        color: #555;
        font-weight: bold;
    }
    .details p span {
        color: #333;
    }
    .details p .highlight {
        font-weight: bold;
        color: #007bff;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
    }
    .footer a {
        text-decoration: none;
        color: #007bff;
        padding: 8px 16px;
        border: 1px solid #007bff;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }
    .footer a:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Support Request Details</h1>
    </div>
    <div class="details">
        <?php
        require 'db_connection.php';

        // Check if 'id' is set in the URL
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']); // Convert 'id' to an integer for security
            
            // Prepare and execute the query to fetch the support request
            $stmt = $conn->prepare("SELECT * FROM support_requests WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the request exists
            if ($result->num_rows > 0) {
                $request = $result->fetch_assoc();
                
                // Display the request details
                echo "<p><strong>ID:</strong> <span class='highlight'>" . $request['id'] . "</span></p>";
                echo "<p><strong>User ID:</strong> <span>" . $request['user_id'] . "</span></p>";
                echo "<p><strong>Subject:</strong> <span>" . htmlspecialchars($request['subject']) . "</span></p>";
                echo "<p><strong>Message:</strong> <span>" . htmlspecialchars($request['message']) . "</span></p>";
                echo "<p><strong>Admin Response:</strong> <span>" . htmlspecialchars($request['admin_response']) . "</span></p>";
                echo "<p><strong>Status:</strong> <span class='highlight'>" . htmlspecialchars($request['status']) . "</span></p>";
                echo "<p><strong>Created At:</strong> <span>" . $request['created_at'] . "</span></p>";
                echo "<p><strong>Updated At:</strong> <span>" . $request['updated_at'] . "</span></p>";

                // Display the update status if it is set
                if (isset($_GET['update_status'])) {
                    echo "<p><strong>Update Status:</strong> <span class='highlight'>" . htmlspecialchars($_GET['update_status']) . "</span></p>";
                } else {
                    echo "<p><strong>Update Status:</strong> <span>Not specified</span></p>";
                }
            } else {
                echo "<p>No request found with the specified ID.</p>";
            }

            // Close the statement and connection
            $stmt->close();
        } else {
            echo "<p>No ID specified. No request details to display.</p>";
        }

        $conn->close();
        ?>
    </div>
    <div class="footer">
        <p><a href="support_request.php">Back to Support Requests</a></p>
    </div>
</div>

</body>
</html>
