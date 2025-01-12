<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/header.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Handle form submissions for book actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']); // Converts the book ID to an integer to prevent SQL injection.
    $action = htmlspecialchars($_POST['action']);

    // Approve a book
    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE books SET status = 'approved' WHERE id = ?");
        $stmt->execute([$book_id]);
        echo "✅ Book approved successfully!";
    
    // Reject a book
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE books SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$book_id]);
        echo "✅ Book rejected successfully!";
    
    // Delete a book
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        echo "✅ Book deleted successfully!";
    }
    // Refreshes the page after 1 second to show the updated list.
    header('Refresh: 1');
    exit();
}

// Fetch books based on the selected status filter
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'all';
$query = "SELECT * FROM books";
$params = [];

// Add a WHERE clause if a specific status is selected
if ($status !== 'all') {
    $query .= " WHERE status = ?";
    $params[] = $status;
}

// Execute the query
$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/admin/manage_books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <div class="manage-books-container">
        <h2>📚 Manage Books</h2>
        <!-- Status filter dropdown -->
        <form method="GET">
            <select name="status" onchange="this.form.submit()">
                <option value="all">All</option>
                <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </form>

        <!-- Book table -->
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
                        <!-- Edit and Delete Actions (Always Available) -->
                        <a href="../user/edit_book.php?id=<?= $book['id'] ?>">✏️ Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                            <button type="submit" name="action" value="delete">🗑️ Delete</button>
                        </form>

                        <!-- Status-based Actions -->
                        <?php if ($book['status'] === 'pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <button type="submit" name="action" value="approve">✅ Approve</button>
                                <button type="submit" name="action" value="reject">❌ Reject</button>
                            </form>
                        <?php elseif ($book['status'] === 'approved'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <button type="submit" name="action" value="reject">❌ Reject</button>
                            </form>
                        <?php elseif ($book['status'] === 'rejected'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <button type="submit" name="action" value="approve">✅ Approve</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <a href="../dashboard.php" class="back-link">⬅️ Back to Dashboard</a>
</body>

</html>