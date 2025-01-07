<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "library_db";

// Use a try-catch block to handle potential connection errors
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
