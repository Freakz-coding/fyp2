<?php
include 'db_connection.php'; // Include your database connection file
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch user data for the user being edited
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT username, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username, $email, $role);
    if (!$stmt->fetch()) {
        // User not found, handle error
        $_SESSION['error'] = "User not found.";
        header("Location: manage_users.php");
        exit();
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage_users.php");
    exit();
}

// Process the form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    
    // Input validation
    if (empty($username) || empty($email) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: edit_user.php?id=$userId");
        exit();
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $userId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully.";
        header("Location: manage_users.php"); // Redirect after successful update
    } else {
        $_SESSION['error'] = "Error updating user: " . $stmt->error;
        header("Location: edit_user.php?id=$userId");
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit User</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="admin" <?php echo ($role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($role === 'user') ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
