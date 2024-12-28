<?php
require 'includes/auth.php';
require 'includes/db.php';

// Fetch All Approved Books
$stmt = $conn->prepare("SELECT * FROM books WHERE status = 'approved'");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
</head>
<body>
    <h2>Books</h2>
    <?php if (count($books) > 0): ?>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td>$<?= htmlspecialchars($book['price']) ?></td>
                    <td>
                        <a href="book_details.php?id=<?= $book['id'] ?>">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No books available at the moment.</p>
    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
