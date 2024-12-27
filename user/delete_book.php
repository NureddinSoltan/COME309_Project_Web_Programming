<?php
require '../includes/auth.php';
require '../includes/db.php';

if (!isset($_GET['id'])) {
    die('❌ Invalid Book ID');
}

$book_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ensure the book belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND uploaded_by = ?");
$stmt->execute([$book_id, $user_id]);

if ($stmt->rowCount()) {
    echo "✅ Book deleted successfully!";
    header('Refresh: 1; URL=my_books.php');
    exit();
} else {
    echo "❌ Unauthorized Access or Book Not Found";
}
?>
