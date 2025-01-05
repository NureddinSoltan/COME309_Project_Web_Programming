<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/alerts.php';
require '../includes/header.php';

// Validate Book ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ Invalid Book ID');
}

$book_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch the book
if ($user_role === 'admin') {
    // Admin can access any book
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
} else {
    // Normal user can only access their own books
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ? AND uploaded_by = ?");
    $stmt->execute([$book_id, $user_id]);
}

$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die('❌ Unauthorized Access or Book Not Found');
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $category = htmlspecialchars($_POST['category']);
    $language = htmlspecialchars($_POST['language']);
    $pages = intval($_POST['pages']);

    // Handle File Uploads
    $book_file_path = $book['book_file'];
    $book_image_path = $book['book_image'];

    if (!empty($_FILES['book_file']['name'])) {
        $uploads_dir_books = __DIR__ . '/../assets/uploads/books/';
        $book_file_path = 'assets/uploads/books/' . basename($_FILES['book_file']['name']);
        move_uploaded_file($_FILES['book_file']['tmp_name'], $uploads_dir_books . basename($_FILES['book_file']['name']));
    }

    if (!empty($_FILES['book_image']['name'])) {
        $uploads_dir_images = __DIR__ . '/../assets/uploads/images/';
        $book_image_path = 'assets/uploads/images/' . basename($_FILES['book_image']['name']);
        move_uploaded_file($_FILES['book_image']['tmp_name'], $uploads_dir_images . basename($_FILES['book_image']['name']));
    }

    // Update book details and set status to 'pending'
    $stmt = $conn->prepare("
        UPDATE books 
        SET title = ?, author = ?, description = ?, price = ?, category = ?, language = ?, pages = ?, book_file = ?, book_image = ?, status = 'pending'
        WHERE id = ?
    ");
    $stmt->execute([
        $title, $author, $description, $price,
        $category, $language, $pages,
        $book_file_path, $book_image_path,
        $book_id
    ]);

    display_alert('✅ Book updated successfully! It is now pending admin approval.', 'success');

    // Redirect based on user role
    if ($user_role === 'admin') {
        header('Location: ../admin/manage_books_frontend.php');
    } else {
        header('Location: my_books_frontend.php');
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/user/edit_book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>✏️ Edit Book</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>">

            <label for="description">Description:</label>
            <textarea id="description" name="description"><?= htmlspecialchars($book['description']) ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" step="0.01" name="price" value="<?= $book['price'] ?>">

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category']) ?>">

            <label for="language">Language:</label>
            <input type="text" id="language" name="language" value="<?= htmlspecialchars($book['language']) ?>">

            <label for="pages">Pages:</label>
            <input type="number" id="pages" name="pages" value="<?= htmlspecialchars($book['pages']) ?>">

            <label for="book_file">Current Book File:</label>
            <?php if (!empty($book['book_file'])): ?>
                <p>📄 <a href="../<?= htmlspecialchars($book['book_file']) ?>" download>Download Current File</a></p>
            <?php endif; ?>
            <input type="file" id="book_file" name="book_file" accept=".pdf">

            <label for="book_image">Current Book Cover Image:</label>
            <?php if (!empty($book['book_image'])): ?>
                <p>🖼️ <img src="../<?= htmlspecialchars($book['book_image']) ?>" alt="Book Cover" width="100"></p>
            <?php endif; ?>
            <input type="file" id="book_image" name="book_image" accept="image/*">

            <button type="submit">💾 Save Changes</button>
        </form>
        <a href="<?= $user_role === 'admin' ? '../admin/manage_books_frontend.php' : 'my_books_frontend.php' ?>" class="back-link">⬅️ Back</a>
    </div>
</body>
</html>