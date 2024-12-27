<?php
require '../includes/auth.php';
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $uploaded_by = $_SESSION['user_id'];

    // Handle File Uploads
    $book_file = $_FILES['book_file'];
    $book_image = $_FILES['book_image'];

    $book_file_path = '../assets/uploads/books/' . basename($book_file['name']);
    $book_image_path = '../assets/uploads/images/' . basename($book_image['name']);

    if (move_uploaded_file($book_file['tmp_name'], $book_file_path) &&
        move_uploaded_file($book_image['tmp_name'], $book_image_path)) {
        
        try {
            $stmt = $conn->prepare("INSERT INTO books (title, author, description, book_file, book_image, price, uploaded_by, status) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$title, $author, $description, $book_file_path, $book_image_path, $price, $uploaded_by]);
            echo "✅ Book uploaded successfully! Pending admin approval.";
        } catch (PDOException $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    } else {
        echo "❌ Failed to upload files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Book</title>
</head>
<body>
    <h2>Upload a Book</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" required><br>
        
        <label>Author:</label><br>
        <input type="text" name="author"><br>
        
        <label>Description:</label><br>
        <textarea name="description"></textarea><br>
        
        <label>Price:</label><br>
        <input type="number" step="0.01" name="price"><br>
        
        <label>Book File (PDF):</label><br>
        <input type="file" name="book_file" accept=".pdf" required><br>
        
        <label>Book Cover Image:</label><br>
        <input type="file" name="book_image" accept="image/*" required><br>
        
        <button type="submit">Upload</button>
    </form>
    <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>
