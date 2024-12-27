<?php
require '../includes/auth.php';
require '../includes/db.php';

if (!isset($_GET['id'])) {
    die('❌ Invalid Book ID');
}

$book_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch the book to ensure it belongs to the logged-in user
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ? AND uploaded_by = ?");
$stmt->execute([$book_id, $user_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die('❌ Unauthorized Access or Book Not Found');
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, description = ?, price = ? WHERE id = ?");
    $stmt->execute([$title, $author, $description, $price, $book_id]);

    echo "✅ Book updated successfully!";
    header('Refresh: 1; URL=my_books.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
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
    <a href="my_books.php">Back to My Books</a>
</body>
</html>
