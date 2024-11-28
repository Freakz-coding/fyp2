<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - LOGISTEED Malaysia Sdn Bhd</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        header {
            background-color: #003366;
            padding: 20px;
        }
        .navbar {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            padding: 10px;
        }
        .navbar a:hover {
            background-color: #0051a2;
            border-radius: 5px;
        }
        .content {
            text-align: center;
            margin: 50px;
        }
        .content h1 {
            color: #003366;
            font-size: 36px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
        }
        .contact-box {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
        }
        .contact-box h2 {
            color: #003366;
            font-size: 30px;
            text-align: center;
            margin-bottom: 20px;
        }
        .contact-box p {
            color: #555;
            line-height: 1.6;
            font-size: 18px;
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .contact-form button {
            background-color: #003366;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
        .contact-form button:hover {
            background-color: #0051a2;
        }
        footer {
            background-color: #003366;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <a href="login.html">Home</a>
        <a href="about.php">About</a>
        <a href="faq.php">FAQ</a>
    </nav>
</header>

<div class="container">
    <div class="contact-box">
        <h2>Contact Us</h2>
        <p>
            If you have any inquiries or need further information, feel free to contact us using the form below. We are here to assist you!
        </p>

        <div class="contact-form">
            <form action="submit_contact.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 LOGISTEED Malaysia Sdn Bhd</p>
</footer>

</body>
</html>
