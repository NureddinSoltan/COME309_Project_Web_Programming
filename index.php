<?php
require 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Library Management System</title>
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="index-container">
        <section class="hero-section">
            <h1>📚 Welcome to Our Library Management System</h1>
            <p>
                Explore the best management platform in the world. Effortlessly manage books, users, and resources
                with our modern and user-friendly interface.
            </p>
            <a href="login.php" class="cta-button">Get Started</a>
        </section>

        <section class="features-section">
            <h2>🌟 Why Choose Us?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-book"></i>
                    <h3>Vast Collection</h3>
                    <p>Access thousands of books across various categories and genres.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>User-Friendly</h3>
                    <p>An intuitive and easy-to-use platform for administrators and users.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Secure Platform</h3>
                    <p>Your data is protected with top-notch security measures.</p>
                </div>
            </div>
        </section>

        <footer>
            <p>&copy; <?= date('Y') ?> Library Management System. All Rights Reserved.</p>
        </footer>
    </div>
</body>

</html>
