<?php
require '../includes/auth.php';
require '../includes/db.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Fetch all comments with user and book details
$stmt = $conn->prepare("
    SELECT comments.id, comments.comment, comments.created_at, users.username, books.title AS book_title
    FROM comments
    JOIN users ON comments.user_id = users.id
    JOIN books ON comments.book_id = books.id
    ORDER BY comments.created_at DESC
");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle comment deletion
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    echo "✅ Comment deleted successfully!";
    header('Refresh: 1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Comments</title>
</head>

<body>
    <h2>Manage Comments</h2>
    <table border="1">
        <tr>
            <th>User</th>
            <th>Book</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?= htmlspecialchars($comment['username']) ?></td>
                <td><?= htmlspecialchars($comment['book_title']) ?></td>
                <td><?= htmlspecialchars($comment['comment']) ?></td>
                <td><?= htmlspecialchars($comment['created_at']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <button type="submit" name="delete_comment">❌ Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../dashboard.php">Back to Dashboard</a>
</body>

</html>