<?php
require 'includes/auth.php';
require 'includes/db.php';

// Ensure a valid book ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('❌ Invalid Book ID');
}

$book_id = $_GET['id'];

// Fetch Book Details
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die('❌ Book Not Found');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Details</title>
</head>

<body>
    <h2>Book Details</h2>
    <p><strong>Title:</strong> <?= htmlspecialchars($book['title']) ?></p>
    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>
    <p><strong>Price:</strong> $<?= htmlspecialchars($book['price']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($book['status']) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
    <p><strong>Language:</strong> <?= htmlspecialchars($book['language']) ?></p>
    <p><strong>Pages:</strong> <?= htmlspecialchars($book['pages']) ?></p>
    <p><strong>Price:</strong> $<?= htmlspecialchars($book['price']) ?></p>

    <?php if (!empty($book['book_image'])): ?>
        <p><strong>Cover Image:</strong></p>
        <img src="<?= htmlspecialchars($book['book_image']) ?>" alt="Book Cover" width="200" style="border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
    <?php else: ?>
        <p><em>No cover image available.</em></p>
    <?php endif; ?>

    <?php if (!empty($book['book_file'])): ?>
        <p><strong>Download Book:</strong></p>
        <a href="<?= htmlspecialchars($book['book_file']) ?>" download>
            📥 Click here to download the book
        </a>
        <?php if (!file_exists($book['book_file'])): ?>
            <p style="color: red;">❌ File not found on the server.</p>
        <?php endif; ?>
    <?php else: ?>
        <p><em>No book file available for download.</em></p>
    <?php endif; ?>


    <h3>Comments</h3>
    <form method="POST">
        <textarea name="comment" placeholder="Write your comment here..." required></textarea><br>
        <button type="submit">Add Comment</button>
    </form>

    <?php
    // Fetch All Comments
    $stmt = $conn->prepare("
        SELECT comments.comment, comments.created_at, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE comments.book_id = ?
        ORDER BY comments.created_at DESC
    ");
    $stmt->execute([$book_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <ul>
        <?php foreach ($comments as $comment): ?>
            <li>
                <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                <?= htmlspecialchars($comment['comment']) ?>
                <small>(<?= htmlspecialchars($comment['created_at']) ?>)</small>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="books.php">Back to Books</a>
</body>

</html>