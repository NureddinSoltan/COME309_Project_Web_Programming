<?php
// Start the session to access session variables
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the homepage
header('Location: index.php');

// Ensure no further code is executed after the redirect
exit();
?>
