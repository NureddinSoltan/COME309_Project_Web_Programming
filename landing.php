<?php
require 'includes/db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch approved books
$stmt = $conn->prepare("SELECT * FROM books WHERE status = 'approved'");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library - Explore Books</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        .book-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            padding: 10px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .book-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .book-card h3 {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>Explore Our Books</h2>
    <div class="book-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <img src="<?= htmlspecialchars($book['book_image']) ?>" alt="Book Cover">
                <h3><?= htmlspecialchars($book['title']) ?></h3>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <a href="book_details.php?id=<?= $book['id'] ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
