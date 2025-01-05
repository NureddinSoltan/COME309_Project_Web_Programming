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

            // Redirect to avoid resubmission on page reload
            header("Location: book_details_frontend.php?id=$book_id&comment=success");
            exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/book_details.css">
</head>

<body>
    <div class="container">
        <!-- Book Details Section -->
        <section class="book-details">
            <h2>📚 Book Details</h2>
            <p><strong>Title:</strong> <?= htmlspecialchars($book['title']) ?></p>
            <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($book['price']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
            <p><strong>Language:</strong> <?= htmlspecialchars($book['language']) ?></p>
            <p><strong>Pages:</strong> <?= htmlspecialchars($book['pages']) ?></p>

            <?php if (!empty($book['book_image'])): ?>
                <div class="book-cover">
                    <img src="<?= htmlspecialchars($book['book_image']) ?>" alt="Book Cover">
                </div>
            <?php endif; ?>

            <?php if (!empty($book['book_file'])): ?>
                <div class="download-section">
                    <a href="<?= htmlspecialchars($book['book_file']) ?>" class="download-btn" download>
                        📥 Download Book
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <!-- Add Comment Section -->
        <section class="comment-section">
    <h3>💬 Add a Comment</h3>
    <?php if (isset($_GET['comment']) && $_GET['comment'] === 'success'): ?>
        <p style="color: green;">✅ Comment added successfully!</p>
    <?php endif; ?>
    <form method="POST" class="comment-form">
        <textarea name="comment" placeholder="Write your comment here..." required></textarea><br>
        <button type="submit">Add Comment</button>
    </form>
</section>


        <!-- Display Comments Section -->
        <section class="comments-list-section">
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
        </section>

        <!-- Back to Books -->
        <div class="back-link">
            <a href="books_frontend.php">
                ⬅️ Back to Books
            </a>
        </div>
    </div>
</body>

</html>