<?php
// Determine Base URL Dynamically
$base_url = (strpos($_SERVER['REQUEST_URI'], '/user/') !== false) ? '../' : '';
$base_url = (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) ? '../' : $base_url;
?>

<nav class="navbar">
    <div class="nav-brand">
        <a href="<?= $base_url ?>landing.php">
            <i class="fas fa-book-reader"></i>
            <span>Library System</span>
        </a>
    </div>
    <div class="nav-user">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= $base_url ?>dashboard.php" class="nav-btn">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= $base_url ?>admin/manage_books.php" class="nav-btn">
                    <i class="fas fa-book"></i> Manage Books
                </a>
            <?php else: ?>
                <a href="<?= $base_url ?>dashboard.php" class="nav-btn">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= $base_url ?>user/upload_book.php" class="nav-btn">
                    <i class="fas fa-upload"></i> Upload Books
                </a>
                <a href="<?= $base_url ?>user/my_books.php" class="nav-btn">
                    <i class="fas fa-book"></i> My Books
                </a>
            <?php endif; ?>
            <a href="<?= $base_url ?>logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php else: ?>
            <a href="<?= $base_url ?>login.php" class="nav-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="<?= $base_url ?>signup.php" class="nav-btn">
                <i class="fas fa-user-plus"></i> Sign Up
            </a>
        <?php endif; ?>
    </div>
</nav>
