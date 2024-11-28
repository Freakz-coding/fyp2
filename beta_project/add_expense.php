<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('db_connection.php'); // Updated to use the new db_connection.php

$showPopup = false; // Variable to check if the popup should show

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expense_date = $_POST['expense_date'];
    $category = $_POST['category'];
    
    // Check if "Others" was selected and use the custom category input if available
    if ($category === 'Others') {
        $category = $_POST['custom_category'];  // Get the custom category
    }
    
    $amount = $_POST['amount'];
    $added_by = $_SESSION['username']; // Assuming the username is stored in the session
    $user_id = $_SESSION['user_id'];   // Get user ID from session

    // Handle file upload (optional)
    $receipt = $_FILES['receipt'];
    $uploadDir = __DIR__ . '\uploads/'; // Directory to store uploaded files
    $uploadFilePath = null; // Initialize file path variable

    // Validate amount to ensure it's a valid number
    if (!is_numeric($amount) || $amount <= 0) {
        echo "<script>alert('Please enter a valid amount.');</script>";
        exit();
    }

    // Check if a file is uploaded
    if ($receipt['error'] != UPLOAD_ERR_NO_FILE) { // Check if file was uploaded
        // Validate file upload
        $allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (in_array($receipt['type'], $allowedFileTypes) && $receipt['size'] <= 2000000) { // Limit file size to 2MB
            // Move the uploaded file to the target directory
            $uploadFilePath = $uploadDir . basename($receipt['name']);
            if (move_uploaded_file($receipt['tmp_name'], $uploadFilePath)) {
                // File uploaded successfully
            } else {
                echo "<script>alert('Error uploading file.');</script>";
                exit();
            }
        } else {
            echo "<script>alert('Invalid file type or size. Please upload a JPEG, PNG, or PDF under 2MB.');</script>";
            exit();
        }
    }

    // Insert the expense into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO expenses (expense_date, category, amount, added_by, user_id, receipt) VALUES (?, ?, ?, ?, ?, ?)");
    
    // If no file is uploaded, set receipt to NULL
    $stmt->bind_param("ssdssi", $expense_date, $category, $amount, $added_by, $user_id, $uploadFilePath);

    if ($stmt->execute()) {
        $showPopup = true; // Set popup flag to true on success
    } else {
        echo "<script>alert('Error adding expense: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
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
            background-color: #EAF2F8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for the form */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            border-left: 5px solid #4A90E2;
        }

        /* Form Heading */
        h2 {
            text-align: center;
            color: #4A90E2;
            margin-bottom: 20px;
        }

        /* Form Labels */
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #4A90E2;
        }

        /* Input Fields */
        form input[type="text"],
        form input[type="number"],
        form input[type="date"],
        form input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        /* Dropdown Styles */
        form select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        /* Button Styles */
        button {
            width: 100%;
            padding: 12px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #357ABD;
        }

        /* Back to Dashboard link */
        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #4A90E2;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Popup Notification */
        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4A90E2;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
            font-size: 14px;
            display: none;
        }

        .popup.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <script>
        function showPopup() {
            const popup = document.getElementById('popup');
            popup.classList.add('show');
            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }

        function toggleCustomCategory() {
            const categorySelect = document.getElementById('category');
            const customCategoryField = document.getElementById('custom_category_field');
            if (categorySelect.value === 'Others') {
                customCategoryField.style.display = 'block';
            } else {
                customCategoryField.style.display = 'none';
            }
        }
    </script>
</head>
<body <?php if ($showPopup) echo 'onload="showPopup()"'; ?>>
    <div class="container">
        <h2>Add New Expense</h2>
        <form method="POST" action="add_expense.php" enctype="multipart/form-data">
            <label for="expense_date">Expense Date:</label>
            <input type="date" id="expense_date" name="expense_date" required><br>

            <label for="category">Category:</label>
            <select id="category" name="category" required onchange="toggleCustomCategory()">
                <option value="Personnel Costs">Personnel Costs</option>
                <option value="Office & Supplies">Office & Supplies</option>
                <option value="Professional Services">Professional Services</option>
                <option value="Software & Technology">Software & Technology</option>
                <option value="Financial Costs">Financial Costs</option>
                <option value="Training & Development">Training & Development</option>
                <option value="Insurance & Compliance">Insurance & Compliance</option>
                <option value="Miscellaneous">Miscellaneous</option>
                <option value="Others">Others (Custom)</option>
            </select><br>

            <!-- Custom Category Input (Initially hidden) -->
            <div id="custom_category_field" style="display:none;">
                <label for="custom_category">Enter Custom Category:</label>
                <input type="text" id="custom_category" name="custom_category"><br>
            </div>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" required><br>

            <label for="receipt">Receipt (optional):</label>
            <input type="file" id="receipt" name="receipt"><br>

            <button type="submit">Add Expense</button>
        </form>

        <a href="user_dashboard.php">Back to Dashboard</a>
    </div>

    <!-- Success Popup -->
    <div id="popup" class="popup">
        Expense added successfully!
    </div>
</body>
</html>
