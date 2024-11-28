<?php
// Hash the password for the admin user
$hashedPassword = password_hash('1234', PASSWORD_DEFAULT);

// Output the hashed password
echo $hashedPassword;
?>
