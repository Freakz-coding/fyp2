<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manual - Business Expenses Tracker</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            padding: 20px 10px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: #fff;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
        }

        header p {
            margin: 10px 0;
            font-size: 1.2rem;
        }

        nav {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            background: #007bff;
            border-radius: 8px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }

        section {
            margin-top: 20px;
        }

        .card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .icon {
            font-size: 2rem;
            color: #007bff;
            margin-right: 10px;
            vertical-align: middle;
        }

        .cta-button {
            display: inline-block;
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #0056b3;
        }

        .back-button {
            display: inline-block;
            background: #ccc;
            color: #333;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #aaa;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
            }

            nav a {
                text-align: center;
                padding: 15px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Business Expenses Tracker</h1>
            <p>Your guide to effective expense management.</p>
        </header>
        <nav>
            <a href="?section=introduction">Introduction</a>
            <a href="?section=setup">Getting Started</a>
            <a href="?section=features">Features</a>
        </nav>
        
        <!-- Back Button -->
        <a href="login.html" class="back-button">Back to Login</a>
        
        <section>
            <?php
            // Dynamic section loader based on the selected tab
            $section = $_GET['section'] ?? 'introduction';

            if ($section === 'introduction') {
                echo "
                <div class='card'>
                    <h2><span class='icon'>📘</span> Introduction</h2>
                    <p>Welcome to the <strong>Business Expenses Tracker</strong>! This system helps you manage your business expenses efficiently and provides real-time insights to support financial decision-making.</p>
                    <a href='?section=setup' class='cta-button'>Get Started</a>
                </div>";
            } elseif ($section === 'setup') {
                echo "
                <div class='card'>
                    <h2><span class='icon'>⚙️</span> Getting Started</h2>
                    <p>To use this system, log in with your credentials such as Email Address and Password on the Login page. Hit the Login button to access the dashboard.</p>
                    <ul>
                        <li>Login Page</li>
                        <img src='userImages1.png' alt='Login Screen' />
                        <li>If you are a new user, follow these steps to create an account:</li>
                        <li>Step 1: Look for the <b>'Don’t have an account? Register here'</b> link below the login form.</li>
                        <img src='userImages1.1.png' alt='Login Screen 2' />
                        <li>Step 2: You will be redirected to the Sign-up Page. Fill in details such as your Name, Email Address, and Password.</li>
                        <img src='userImages2.png' alt='Sign up Screen' />
                    </ul>
                </div>";
            } elseif ($section === 'features') {
                echo "
                <div class='card'>
                    <h2><span class='icon'>✨</span> Features</h2>
                    <p>The system offers the following features:</p>
                    <ul>
                        <li>Dashboard Overview.</li>
                        <li>Step 1: After logging in, you will be directed to the Expense Dashboard.</li>
                        <li>Step 2: The main sections on the dashboard include:<br>
                        - <b>Total Expenses (Month & Year):</b> Displays the total expenses for the current month and year.<br>
                        - <b>Recent Transactions:</b> Shows your latest expense records in a table.<br>
                        - <b>Charts:</b> View expenses by category and trends over the months.<br>
                        - <b>Alerts & Insights:</b> Real-time alerts and actionable insights to help you track spending patterns.</li>
                        <img src='userImages4.png' alt='Dashboard Screen' />
                        
                       
                        <li>Adding an Expense</li>
                        <li>Step 1: Click on the Add Expense button from the menu bar.</li>
                        <img src='userImages5.png' alt='Dashboard Screen' />
                        <li>Step 2: Fill out the expense details:<br>
                        - <b>Date:</b> View expenses by category and trends over the months.<br>
                        - <b>Category:</b> Select the appropriate category for your expense.<br>
                        - <b>Amount:</b> Enter the expense amount.<br>
                        - <b>Receipt:</b> You can upload any files.</li>
                        <li>Step 3: Click <b>Add Expense</b> to add the expense.</li>
                        <img src='userImages5.1.png' alt='Dashboard Screen' />
                    </ul>
                </div>";
            } else {
                echo "
                <div class='card'>
                    <h2>Welcome!</h2>
                    <p>Please select a section from the menu above.</p>
                </div>";
            }
            ?>
        </section>
    </div>
</body>
</html>
