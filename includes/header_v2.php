<nav>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="books.php">📚 Browse Books</a>
    <a href="landing.php">🌟 Explore Books</a>
    <a href="user/my_books.php">📤 My Books</a>
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <a href="admin/manage_users.php">👥 Manage Users</a>
        <a href="admin/manage_books.php">📦 Manage Books</a>
    <?php endif; ?>
    <a href="logout.php" style="color: red;">🚪 Logout</a>
</nav>
<hr>
