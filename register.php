<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        echo "✅ Registration successful! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>User Registration</h2>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
