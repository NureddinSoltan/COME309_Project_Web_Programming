<?php
require '../includes/auth.php';
require '../includes/db.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $action = htmlspecialchars($_POST['action']);

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE books SET status = 'approved' WHERE id = ?");
        $stmt->execute([$book_id]);
        echo "✅ Book approved successfully!";
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE books SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$book_id]);
        echo "✅ Book rejected successfully!";
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        echo "✅ Book deleted successfully!";
    }
    header('Refresh: 1');
    exit();
}

// Fetch Books
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'all';
$query = "SELECT * FROM books";
$params = [];

if ($status !== 'all') {
    $query .= " WHERE status = ?";
    $params[] = $status;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <h2>Manage Books</h2>

    <form method="GET">
        <select name="status" onchange="this.form.submit()">
            <option value="all">All</option>
            <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
    </form>

    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Language</th>
            <th>Pages</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><a href="../book_details.php?id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></a></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['category']) ?></td>
                <td><?= htmlspecialchars($book['language']) ?></td>
                <td><?= htmlspecialchars($book['pages']) ?></td>
                <td>$<?= htmlspecialchars($book['price']) ?></td>
                <td><?= htmlspecialchars($book['status']) ?></td>
                <td>
                    <a href="../user/edit_book.php?id=<?= $book['id'] ?>">✏️ Edit</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button type="submit" name="action" value="delete">🗑️ Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../dashboard.php">⬅️ Back to Dashboard</a>
</body>

</html>