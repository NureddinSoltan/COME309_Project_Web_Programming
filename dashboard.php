<?php
require 'includes/auth.php';
require 'includes/db.php';

echo "<h1>Welcome, " . $_SESSION['username'] . "!</h1>";
echo "<p>User Role: " . $_SESSION['user_role'] . "</p>"; // Check user role here
?>
