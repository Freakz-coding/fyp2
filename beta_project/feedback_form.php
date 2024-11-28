<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8fafc;
            color: #1e40af;
        }
        .header {
            background-color: #1d4ed8;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card {
            margin: 20px auto;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            border-radius: 8px;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Provide Feedback</h1>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">We value your feedback!</h5>
                <form action="submit_feedback.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Feedback Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="bug">Bug Report</option>
                            <option value="feature">Feature Request</option>
                            <option value="general">General Feedback</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Your Feedback</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attach File (optional)</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom">Submit Feedback</button>
                </form>
                <p class="mt-3"><a href="user_dashboard.php">Go Back</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
