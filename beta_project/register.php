<?php
// Include the database connection file
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Set a default role for new users (you can change this logic as needed)
    $role = 'user'; // Default role is 'user'

    // Check if email is already registered
    $checkEmail = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
        echo "<script>
            alert('This email is already registered.');
            window.history.back();
        </script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password, role, limit_expense) VALUES ('$username', '$email', '$hashed_password', '$role', '1000')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful, popup notification and redirect
        echo "<script>
            alert('Registration successful! You will be redirected to the login page.');
            window.location.href = 'login.html';  // Redirect to login page
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
