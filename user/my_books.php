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
    <title>My Books</title>
</head>
<body>
    <h2>My Uploaded Books</h2>
    <a href="upload_book.php">Upload New Book</a>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['status']) ?></td>
                <td>
                    <a href="edit_book.php?id=<?= $book['id'] ?>">Edit</a> | 
                    <a href="delete_book.php?id=<?= $book['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>
