<?php
require '../includes/auth.php';
require '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access. Only admin can access this page.');
}

$stmt = $conn->prepare("SELECT * FROM books WHERE status = 'pending'");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Books | Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin/pending_books_frontend.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <i class="fas fa-shield-alt logo"></i>
                <span class="nav-title">Admin Panel</span>
                <a href="../dashboard.php" class="nav-link">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container main">
        <div class="header">
            <h1>Pending Books Review</h1>
            <span class="status-badge"><?= count($books) ?> Books Pending Review</span>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <i class="fas fa-book"></i>
                                <?= htmlspecialchars($book['title']) ?>
                            </td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td>
                                <form method="POST" class="actions">
                                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                    <button type="submit" name="action" value="approved" class="btn approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="submit" name="action" value="rejected" class="btn reject">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
