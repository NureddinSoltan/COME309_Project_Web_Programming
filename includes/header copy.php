<nav class="navbar">
        <div class="nav-brand">
            <a href="landing.php">
                <i class="fas fa-book-reader"></i>
                <span>Library System</span>
            </a>
        </div>
        <div class="nav-user">
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>