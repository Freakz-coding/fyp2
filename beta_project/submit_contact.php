<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize the form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Check if the necessary fields are filled
    if (!empty($name) && !empty($email) && !empty($message)) {
        
        // Optional: Send an email notification (if required)
        $to = "your-email@example.com"; // Replace with your email address
        $subject = "New Contact Form Submission";
        $body = "You have received a new message from $name.\n\n";
        $body .= "Email: $email\n\n";
        $body .= "Message:\n$message";
        $headers = "From: $email";
        
        if (mail($to, $subject, $body, $headers)) {
            echo "Thank you for contacting us! We'll get back to you soon.";
        } else {
            echo "There was an error sending your message. Please try again later.";
        }
        
    } else {
        echo "Please fill in all fields.";
    }
} else {
    // Not a POST request
    echo "Invalid request method.";
}
?>
