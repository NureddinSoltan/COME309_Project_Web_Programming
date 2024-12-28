<?php
require '../includes/auth.php';
require '../includes/db.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Handle Actions (Approve, Reject, Delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

// Fetch Books Based on Status
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
    <style>
        .actions button {
            margin-right: 5px;
        }
        .status-rejected {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Manage Books</h2>

    <!-- Filter Books -->
    <form method="GET" style="margin-bottom: 20px;">
        <select name="status" onchange="this.form.submit()">
            <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All</option>
            <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
    </form>

    <!-- Display Books -->
    <?php if (count($books) > 0): ?>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <!-- Title as a Link -->
                    <td>
                        <a href="../book_details.php?id=<?= $book['id'] ?>">
                            <?= htmlspecialchars($book['title']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['status']) ?></td>
                    <td class="actions">
                        <?php if ($book['status'] === 'pending'): ?>
                            <!-- Pending Actions -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <button type="submit" name="action" value="approve">✅ Approve</button>
                                <button type="submit" name="action" value="reject">❌ Reject</button>
                            </form>
                        <?php elseif ($book['status'] === 'approved'): ?>
                            <!-- Approved Actions -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <button type="submit" name="action" value="delete">🗑️ Delete</button>
                            </form>
                            <a href="../user/edit_book.php?id=<?= $book['id'] ?>">✏️ Edit</a>
                        <?php elseif ($book['status'] === 'rejected'): ?>
                            <!-- Rejected Status -->
                            <span class="status-rejected">❌</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No books found for the selected status.</p>
    <?php endif; ?>

    <a href="../dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
