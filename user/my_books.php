<?php
require '../includes/auth.php';
require '../includes/db.php';

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
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>My Uploaded Books</h2>
    <a href="upload_book.php"><button>Upload New Book</button></a>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><a href="../book_details.php?id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></a></td>
                <td><?= htmlspecialchars($book['status']) ?></td>
                <td>
                    <?php if ($book['status'] === 'pending'): ?>
                        ⏳ Pending Approval
                    <?php else: ?>
                        <a href="edit_book.php?id=<?= $book['id'] ?>">✏️ Edit</a>
                        <a href="delete_book.php?id=<?= $book['id'] ?>">🗑️ Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
