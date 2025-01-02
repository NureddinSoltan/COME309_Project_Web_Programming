<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

// Admin Stats
if ($_SESSION['user_role'] === 'admin') {
    $total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $total_books = $conn->query("SELECT COUNT(*) FROM books")->fetchColumn();
    $pending_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'pending'")->fetchColumn();
    $approved_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'approved'")->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Library Management System</title>
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>


    <div class="container">
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <header class="dashboard-header">
                <h1>Admin Dashboard</h1>
                <p class="subtitle">System Overview</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card users">
                    <i class="fas fa-users"></i>
                    <h3>Total Users</h3>
                    <p class="stat-number"><?= $total_users ?></p>
                </div>
                <div class="stat-card books">
                    <i class="fas fa-book"></i>
                    <h3>Total Books</h3>
                    <p class="stat-number"><?= $total_books ?></p>
                </div>
                <div class="stat-card pending">
                    <i class="fas fa-clock"></i>
                    <h3>Pending Books</h3>
                    <p class="stat-number"><?= $pending_books ?></p>
                </div>
                <div class="stat-card approved">
                    <i class="fas fa-check-circle"></i>
                    <h3>Approved Books</h3>
                    <p class="stat-number"><?= $approved_books ?></p>
                </div>
                <?php
                $rejected_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'rejected'")->fetchColumn();
                ?>
                <div class="stat-card rejected">
                    <i class="fas fa-times-circle"></i>
                    <h3>Rejected Books</h3>
                    <p class="stat-number"><?= $rejected_books ?></p>
                </div>
            </div>

            <section class="admin-actions">
                <h2>Management Tools</h2>
                <div class="action-grid">
                    <a href="admin/manage_users.php" class="action-card">
                        <i class="fas fa-users-cog"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="admin/pending_books.php" class="action-card">
                        <i class="fas fa-tasks"></i>
                        <span>Pending Books</span>
                    </a>
                    <a href="admin/manage_comments.php" class="action-card">
                        <i class="fas fa-comments"></i>
                        <span>Manage Comments</span>
                    </a>
                    <a href="admin/manage_books.php" class="action-card">
                    <i class="fa-solid fa-book"></i>
                        <span>Manage Books</span>
                    </a>
                    <a href="books.php" class="action-card">
                    <i class="fas fa-search"></i>
                        <span>Browse Books</span>
                    </a>
                </div>
            </section>

        <?php else: ?>
            <header class="dashboard-header">
                <h1>User Dashboard</h1>
                <p class="subtitle">Manage Your Books</p>
            </header>

            <section class="user-actions">
                <div class="action-grid">
                    <a href="user/my_books.php" class="action-card">
                        <i class="fas fa-books"></i>
                        <span>My Books</span>
                    </a>
                    <a href="user/upload_book.php" class="action-card">
                        <i class="fas fa-upload"></i>
                        <span>Upload New Book</span>
                    </a>
                    <a href="books.php" class="action-card">
                        <i class="fas fa-search"></i>
                        <span>Browse Books</span>
                    </a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</body>

</html>