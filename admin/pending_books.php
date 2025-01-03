<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/header.php';

// Ensure only admin can access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access. Only admin can access this page.');
}

// $stmt = $conn->prepare("SELECT * FROM books WHERE status = 'pending'");
// $stmt->execute();
// $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM books WHERE status = 'pending'");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Approval/Rejection
if (isset($_POST['action']) && isset($_POST['book_id'])) {
    $action = $_POST['action'];
    $book_id = $_POST['book_id'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE books SET status = ? WHERE id = ?");
        $stmt->execute([$action, $book_id]);
        echo "✅ Book status updated!";
        header('Refresh: 1');
        exit();
    } else {
        echo "❌ Invalid action.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Books</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <!-- <link rel="stylesheet" href="../assets/css/admin/manage_books.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <h2>Pending Books</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button type="submit" name="action" value="approved">✅ Approve</button>
                        <button type="submit" name="action" value="rejected">❌ Reject</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>

