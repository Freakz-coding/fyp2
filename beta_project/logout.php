<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page (HTML file)
header("Location: login.html");
exit();
?>
