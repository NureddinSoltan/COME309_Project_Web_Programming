<?php
require 'includes/auth.php';
require 'includes/db.php';

echo "<h1>Welcome, " . $_SESSION['username'] . "!</h1>";
echo "<p>You are logged in as: " . $_SESSION['user_role'] . "</p>";
echo "<a href='logout.php'>Logout</a>";
?>
