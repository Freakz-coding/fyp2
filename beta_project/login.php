<?php
session_start(); // Start the session at the very top

include('db_connection.php'); // Ensure the path is correct

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify user and password
        if ($user && password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store the role in session
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            // Display an error message and redirect to login.html
            echo "<script>
                    alert('Invalid username or password.');
                    window.location.href = 'login.html';
                  </script>";
            exit();
        }
    } else {
        // Handle statement preparation failure
        die("Error preparing statement: " . $conn->error);
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
