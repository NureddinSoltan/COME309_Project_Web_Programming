<?php
require '../includes/auth.php';
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize Input
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $uploaded_by = $_SESSION['user_id'];

    // File Upload Directories
    $uploads_dir_books = __DIR__ . '/../assets/uploads/books/';
    $uploads_dir_images = __DIR__ . '/../assets/uploads/images/';

    // Ensure directories exist
    if (!is_dir($uploads_dir_books)) mkdir($uploads_dir_books, 0755, true);
    if (!is_dir($uploads_dir_images)) mkdir($uploads_dir_images, 0755, true);

    // Handle File Uploads
    $book_file_path = 'assets/uploads/books/' . basename($_FILES['book_file']['name']);
    $book_image_path = 'assets/uploads/images/' . basename($_FILES['book_image']['name']);

    // Normalize paths (replace backslashes with forward slashes)
    $book_file_path = str_replace('\\', '/', $book_file_path);
    $book_image_path = str_replace('\\', '/', $book_image_path);

    // Move Uploaded Files
    $book_file_uploaded = move_uploaded_file(
        $_FILES['book_file']['tmp_name'],
        $uploads_dir_books . basename($_FILES['book_file']['name'])
    );

    $book_image_uploaded = move_uploaded_file(
        $_FILES['book_image']['tmp_name'],
        $uploads_dir_images . basename($_FILES['book_image']['name'])
    );

    if ($book_file_uploaded && $book_image_uploaded) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO books (title, author, description, book_file, book_image, price, uploaded_by, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([
                $title,
                $author,
                $description,
                $book_file_path,
                $book_image_path,
                $price,
                $uploaded_by
            ]);

            echo "✅ Book uploaded successfully! Pending admin approval.";
        } catch (PDOException $e) {
            echo "❌ Database Error: " . $e->getMessage();
        }
    } else {
        echo "❌ Failed to upload files. Please check file permissions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        form input, form textarea, form button {
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
        }
        form button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background: #45a049;
        }
    </style>
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

    <p style="text-align: center;">
        <a href="../dashboard.php">⬅️ Back to Dashboard</a>
    </p>
</body>
</html>
