<?php
session_start();

// Check if the 'user_id' session variable is not set
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
