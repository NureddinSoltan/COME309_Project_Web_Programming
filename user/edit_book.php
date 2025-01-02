<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/alerts.php';

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

    // Update book details and set status to 'pending'
    $stmt = $conn->prepare("
        UPDATE books 
        SET title = ?, author = ?, description = ?, price = ?, status = 'pending'
        WHERE id = ?
    ");
    $stmt->execute([$title, $author, $description, $price, $book_id]);

    display_alert('✅ Book updated successfully! It is now pending admin approval.', 'success');

    // Redirect based on user role
    if ($user_role === 'admin') {
        header('Location: ../admin/manage_books.php');
    } else {
        header('Location: my_books.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>Edit Book</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br>

        <label>Author:</label><br>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>"><br>

        <label>Description:</label><br>
        <textarea name="description"><?= htmlspecialchars($book['description']) ?></textarea><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="<?= $book['price'] ?>"><br>

        <button type="submit">Save Changes</button>
    </form>
    <a href="<?= $user_role === 'admin' ? '../admin/manage_books.php' : 'my_books.php' ?>">⬅️ Back</a>
</body>
</html>
