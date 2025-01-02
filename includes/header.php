<nav class="navbar">
    <div class="nav-brand">
        <a href="landing.php">
            <i class="fas fa-book-reader"></i>
            <span>Library System</span>
        </a>
    </div>
    <div class="nav-user">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="dashboard.php" class="nav-btn">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="admin/manage_books.php" class="nav-btn">
                    <i class="fas fa-book"></i> Manage Books
                </a>
            <?php else: ?>
                <a href="dashboard.php" class="nav-btn">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="user/upload_book.php" class="nav-btn">
                    <i class="fas fa-upload"></i> Upload Books
                </a>
            <?php endif; ?>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php else: ?>
            <a href="login.php" class="nav-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="signup.php" class="nav-btn">
                <i class="fas fa-user-plus"></i> Sign Up
            </a>
        <?php endif; ?>
    </div>
</nav>
