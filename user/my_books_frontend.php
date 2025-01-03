<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch user's books
$stmt = $conn->prepare("SELECT * FROM books WHERE uploaded_by = ?");
$stmt->execute([$user_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Uploaded Books</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/user/my_books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="my-books-container">
        <h2>📚 My Uploaded Books</h2>
        <a href="upload_book.php" class="upload-btn">➕ Upload New Book</a>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><a href="../book_details.php?id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></a></td>
                        <td><?= htmlspecialchars($book['status']) ?></td>
                        <td>
                            <?php if ($book['status'] === 'pending'): ?>
                                <span class="pending">⏳ Pending Approval</span>
                            <?php else: ?>
                                <a href="edit_book.php?id=<?= $book['id'] ?>" class="edit">✏️ Edit</a>
                                <a href="delete_book.php?id=<?= $book['id'] ?>" class="delete">🗑️ Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../dashboard.php" class="back-link">⬅️ Back to Dashboard</a>
    </div>
</body>

</html>