<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/alerts.php';
require '../includes/header.php';

// Handling Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs to prevent XSS attacks
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $category = htmlspecialchars($_POST['category']);
    $language = htmlspecialchars($_POST['language']);
    $pages = intval($_POST['pages']);
    $uploaded_by = $_SESSION['user_id'];

    // Define directories for storing uploaded files
    $uploads_dir_books = __DIR__ . '/../assets/uploads/books/';
    $uploads_dir_images = __DIR__ . '/../assets/uploads/images/';

    // Creates the upload directories if they don’t already exist.
    if (!is_dir($uploads_dir_books)) mkdir($uploads_dir_books, 0755, true);
    if (!is_dir($uploads_dir_images)) mkdir($uploads_dir_images, 0755, true);

    // Extracts the file name from the uploaded file.
    $book_file_path = 'assets/uploads/books/' . basename($_FILES['book_file']['name']);
    $book_image_path = 'assets/uploads/images/' . basename($_FILES['book_image']['name']);

    // Moves the uploaded files to the appropriate directories.
    move_uploaded_file($_FILES['book_file']['tmp_name'], $uploads_dir_books . basename($_FILES['book_file']['name']));
    move_uploaded_file($_FILES['book_image']['tmp_name'], $uploads_dir_images . basename($_FILES['book_image']['name']));

    // Saving Book Details to the Database
    $stmt = $conn->prepare("
        INSERT INTO books (title, author, description, book_file, book_image, price, category, language, pages, uploaded_by, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $title,
        $author,
        $description,
        $book_file_path,
        $book_image_path,
        $price,
        $category,
        $language,
        $pages,
        $uploaded_by
    ]);

    display_alert('✅ Book uploaded successfully! Pending admin approval.', 'success');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/user/upload_book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style></style>
<body>

    <div class="container">
        <h2>📤 Upload a New Book</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required>

            <label>Author:</label>
            <input type="text" name="author">

            <label>Description:</label>
            <textarea name="description"></textarea>

            <label>Price:</label>
            <input type="number" step="0.01" name="price">

            <label>Category:</label>
            <input type="text" name="category" placeholder="e.g., Fiction, Non-Fiction">

            <label>Language:</label>
            <input type="text" name="language" placeholder="e.g., English, Arabic">

            <label>Pages:</label>
            <input type="number" name="pages">

            <label>Book File (PDF):</label>
            <input type="file" name="book_file" accept=".pdf" required>

            <label>Book Cover Image:</label>
            <input type="file" name="book_image" accept="image/*" required>

            <button type="submit">Upload</button>
        </form>
    </div>
</body>

</html>
