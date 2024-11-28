<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('db_connection.php');

// Initialize variables
$username = '';
$email = '';
$message = '';
$message_type = ''; // To differentiate success and error messages

// Fetch current user data
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
    $username = $user['username'];
    $email = $user['email'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    // Validate email format
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $message_type = "error";
    } else {
        // Update user information
        $query = "UPDATE users SET username = '$new_username', email = '$new_email'";
        
        // Update password only if it's provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query .= ", password = '$hashed_password'";
        }
        
        $query .= " WHERE id = '$user_id'";
        if (mysqli_query($conn, $query)) {
            $message = "Settings updated successfully.";
            $message_type = "success";
        } else {
            $message = "Error updating settings: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
            background-color: #f0f8ff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container for the form */
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        /* Form Heading */
        h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
        }

        /* Form Labels */
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #0056b3;
        }

        /* Input Fields */
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Button Styles */
        button {
            width: 100%;
            padding: 12px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #004494;
        }

        /* Notification Styles */
        .notification {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .notification.success {
            background-color: #d4edda; /* Light green */
            color: #155724; /* Dark green */
            border: 1px solid #c3e6cb; /* Dark green border */
        }

        .notification.error {
            background-color: #f8d7da; /* Light red */
            color: #721c24; /* Dark red */
            border: 1px solid #f5c6cb; /* Dark red border */
        }

        /* Back to Dashboard link */
        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #0056b3;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User Profile</h2>
        
        <?php if ($message): ?>
            <div class="notification <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="settings.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank if not changing">

            <button type="submit">Update Settings</button>
        </form>

        <a href="user_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
