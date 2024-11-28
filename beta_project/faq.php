<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - LOGISTEED Malaysia</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* FAQ page styling */
        .faq-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-container h1 {
            text-align: center;
            font-size: 32px;
            color: #005d77;
            margin-bottom: 20px;
        }

        .faq-item {
            background-color: #f9f9f9;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .faq-header {
            padding: 15px;
            background-color: #005d77;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-header:hover {
            background-color: #007b8b;
        }

        .faq-body {
            padding: 15px;
            display: none;
            background-color: #f1f1f1;
            font-size: 16px;
            line-height: 1.6;
        }

        .faq-body p {
            margin: 0;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .toggle-icon.open {
            transform: rotate(180deg);
        }

        /* Contact Us Button */
        .contact-btn {
            display: block;
            margin: 30px auto;
            padding: 15px 25px;
            background-color: #007b8b;
            color: white;
            font-size: 18px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }

        .contact-btn:hover {
            background-color: #005d77;
        }

        /* Back to Login Button */
        .back-to-login {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 10px 20px;
            text-align: center;
            background-color: #005d77;
            color: white;
            font-size: 18px;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .back-to-login:hover {
            background-color: #007b8b;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .faq-container {
                padding: 20px;
            }

            .faq-container h1 {
                font-size: 28px;
            }

            .faq-header {
                font-size: 16px;
            }

            .faq-body {
                font-size: 14px;
            }

            .contact-btn {
                font-size: 16px;
            }

            .back-to-login {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="faq-container">
        <h1>Frequently Asked Questions</h1>

        <div class="faq-item">
            <div class="faq-header">
                <span>What is LOGISTEED Malaysia?</span>
                <i class="bx bx-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-body">
                <p>LOGISTEED Malaysia is a leading third-party logistics (3PL) company specializing in supply chain management and business solutions. Our system helps manage business expenses efficiently for organizations.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-header">
                <span>How does the Business Expenses Tracker work?</span>
                <i class="bx bx-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-body">
                <p>Our Business Expenses Tracker allows businesses to track their expenses, categorize them, and generate detailed financial reports. It streamlines accounting and financial management for companies.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-header">
                <span>Who can use this system?</span>
                <i class="bx bx-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-body">
                <p>The system is designed for financial managers, accountants, and any role involved in business financial oversight. It provides an easy way to monitor, manage, and analyze expenses in real-time.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-header">
                <span>Is the system secure for managing business data?</span>
                <i class="bx bx-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-body">
                <p>Yes! The system uses advanced encryption techniques and secure cloud infrastructure to protect all business data. Only authorized users with specific roles can access sensitive financial information.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-header">
                <span>Can I export financial reports?</span>
                <i class="bx bx-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-body">
                <p>Absolutely! You can export all financial reports into various formats such as CSV, Excel, or PDF, making it easy to share and analyze data across your organization.</p>
            </div>
        </div>

        <a href="contact.php" class="contact-btn">Contact Us</a>

        <!-- Back to Login Link -->
        <a href="login.html" class="back-to-login">Back to Login</a>
    </div>

    <script>
        // FAQ Accordion Toggle
        const faqHeaders = document.querySelectorAll('.faq-header');
        faqHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const faqBody = header.nextElementSibling;
                const icon = header.querySelector('.toggle-icon');
                faqBody.style.display = faqBody.style.display === 'block' ? 'none' : 'block';
                icon.classList.toggle('open');
            });
        });
    </script>

</body>

</html>
