<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/alerts.php';

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

    // Validate File Types
    $allowed_file_types = ['application/pdf'];
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/webp'];

    $book_file_type = $_FILES['book_file']['type'];
    $book_image_type = $_FILES['book_image']['type'];

    if (!in_array($book_file_type, $allowed_file_types)) {
        display_alert('❌ Invalid book file format. Only PDF is allowed.', 'error');
        exit();
    }

    if (!in_array($book_image_type, $allowed_image_types)) {
        display_alert('❌ Invalid image format. Only JPEG, PNG, and WEBP are allowed.', 'error');
        exit();
    }

    // Normalize paths for cross-platform compatibility
    $book_file_path = 'assets/uploads/books/' . basename($_FILES['book_file']['name']);
    $book_image_path = 'assets/uploads/images/' . basename($_FILES['book_image']['name']);

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

            display_alert('✅ Book uploaded successfully! Pending admin approval.', 'success');
        } catch (PDOException $e) {
            display_alert('❌ Database Error: ' . $e->getMessage(), 'error');
        }
    } else {
        display_alert('❌ Failed to upload files. Please check file permissions.', 'error');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book</title>
    <!-- <link rel="stylesheet" href="../assets/css/upload_book.css"> -->

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
        .alert {
            margin: 10px auto;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
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
