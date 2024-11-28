<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | LOGISTEED Malaysia Sdn. Bhd.</title>
    <meta name="description" content="Learn about LOGISTEED Malaysia Sdn. Bhd., our history, services, strategic locations, and contact information.">
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f7fa;
            color: #333;
            line-height: 1.8;
            padding: 20px;
            scroll-behavior: smooth;
        }

        header {
            text-align: center;
            padding: 20px 0;
            background-color: #002d72;
            color: #fff;
        }

        header h1 {
            font-size: 2.5em;
            margin-bottom: 5px;
        }

        nav {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a.active, nav a:hover {
            background-color: #004aad;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            color: #002d72;
            font-size: 1.8em;
            margin-bottom: 10px;
            border-left: 5px solid #004aad;
            padding-left: 10px;
        }

        .section p {
            margin: 10px 0;
            color: #555;
        }

        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 10px;
        }

        .service-item {
            padding: 15px;
            background-color: #e6f2ff;
            border-radius: 5px;
            color: #002d72;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .service-item:hover {
            transform: scale(1.05);
            background-color: #cce7ff;
        }

        .contact-info {
            padding: 15px;
            background-color: #004aad;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1em;
        }

        .contact-info h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #002d72;
            color: #fff;
            margin-top: 20px;
            font-size: 0.9em;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            nav {
                flex-direction: column;
                align-items: center;
            }
            .services {
                grid-template-columns: 1fr;
            }
        }

        /* Make the address text and phone number text white */
        .address-text, .phone-text {
            color: #fff;
        }
    </style>
</head>
<body>

    <header>
        <h1>About Us</h1>
        <nav>
            <a href="login.html">Home</a>
            <a href="about.php" class="active">About Us</a>
            <a href="faq.php">FAQ</a>
        </nav>
    </header>

    <div class="container">
        <div class="section">
            <h2>Our History</h2>
            <p>Established in December 1988, LOGISTEED Malaysia Sdn. Bhd. has grown into a leading provider of logistics services as part of the LOGISTEED Group. We offer reliable logistics solutions across various domains, including warehousing, domestic distribution, and international freight forwarding.</p>
        </div>

        <div class="section">
            <h2>Our Services</h2>
            <p>We provide tailored logistics solutions designed to meet diverse customer needs:</p>
            <div class="services">
                <div class="service-item">Ambient and Temperature-Controlled Warehousing</div>
                <div class="service-item">Domestic Transportation and Distribution</div>
                <div class="service-item">Freight Forwarding Services</div>
                <div class="service-item">In-House Logistic Solutions</div>
                <div class="service-item">Consulting and Strategic Solutions</div>
            </div>
        </div>

        <div class="section">
            <h2>Strategic Locations</h2>
            <p>Our main facilities in Bangi, Selangor, and Nilai, Negeri Sembilan, are strategically positioned for easy access to key expressways, sea ports, and international airports. These locations allow us to ensure seamless logistics services across Malaysia and beyond.</p>
            <p>Our Nilai Cold Warehouse, a recent addition, enables us to meet the growing demands for cold chain distribution, supporting our customers' needs in temperature-sensitive logistics.</p>
        </div>

        <div class="section">
            <h2>Contact Us</h2>
            <div class="contact-info">
                <h3>LOGISTEED Malaysia Sdn. Bhd.</h3>
                <p><span class="address-text">Lot 3, Jalan 6C/12, Seksyen 16,<br>43650 Bandar Baru Bangi,<br>Selangor, Malaysia.</span></p>
                <p><span class="phone-text">Phone: +603 8913 1000 | Fax: +603 8913 1001</span></p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 LOGISTEED Malaysia Sdn. Bhd. All rights reserved.</p>
        <a href="#">Sitemap</a> | 
        <a href="#">Terms of Use</a> | 
        <a href="#">Privacy Policy</a>
    </footer>

</body>
</html>
