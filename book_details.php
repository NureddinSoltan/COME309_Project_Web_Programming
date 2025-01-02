<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

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

// Handle Comment Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if (!empty($comment)) {
        try {
            // Add comment to the comments table
            $stmt = $conn->prepare("
                INSERT INTO comments (book_id, user_id, comment) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$book_id, $user_id, $comment]);

            echo "<p style='color: green;'>✅ Comment added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Comment cannot be empty.</p>";
    }
}

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Details</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/includes/header.css">

    <style>
        .comment-form textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .comment-form button {
            padding: 8px 12px;
            border-radius: 5px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .comment-list {
            list-style: none;
            padding: 0;
        }
        .comment-list li {
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <h2>Book Details</h2>
    <p><strong>Title:</strong> <?= htmlspecialchars($book['title']) ?></p>
    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>
    <p><strong>Price:</strong> $<?= htmlspecialchars($book['price']) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
    <p><strong>Language:</strong> <?= htmlspecialchars($book['language']) ?></p>
    <p><strong>Pages:</strong> <?= htmlspecialchars($book['pages']) ?></p>

    <?php if (!empty($book['book_image'])): ?>
        <p><strong>Cover Image:</strong></p>
        <img src="<?= htmlspecialchars($book['book_image']) ?>" alt="Book Cover" width="200">
    <?php endif; ?>

    <?php if (!empty($book['book_file'])): ?>
        <p><strong>Download Book:</strong></p>
        <a href="<?= htmlspecialchars($book['book_file']) ?>" download>📥 Download Book</a>
    <?php endif; ?>

    <h3>💬 Add a Comment</h3>
    <form method="POST" class="comment-form">
        <textarea name="comment" placeholder="Write your comment here..." required></textarea><br>
        <button type="submit">Add Comment</button>
    </form>

    <h3>📝 Comments</h3>
    <ul class="comment-list">
    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
            <li>
                <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                <?= htmlspecialchars_decode($comment['comment']) ?>
                <small>(<?= htmlspecialchars($comment['created_at']) ?>)</small>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No comments yet. Be the first to comment!</li>
    <?php endif; ?>
</ul>

    <a href="books.php">⬅️ Back to Books</a>
</body>

</html>
