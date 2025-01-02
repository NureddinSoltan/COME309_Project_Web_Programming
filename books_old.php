<?php
require 'includes/auth.php';
require 'includes/db.php';

$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
$language = isset($_GET['language']) ? htmlspecialchars($_GET['language']) : '';
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'approved';

// Base Query
$query = "SELECT * FROM books WHERE status = ?";
$params = [$status];

// Apply Search
if (!empty($search)) {
    $query .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Apply Category Filter
if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

// Apply Language Filter
if (!empty($language)) {
    $query .= " AND language = ?";
    $params[] = $language;
}

// Execute Query
$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h2>📚 Browse Books</h2>

    <!-- Search and Filter Form -->
    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search by Title or Author" value="<?= $search ?>">
        
        <select name="category">
            <option value="">All Categories</option>
            <option value="Fiction" <?= $category == 'Fiction' ? 'selected' : '' ?>>Fiction</option>
            <option value="Non-Fiction" <?= $category == 'Non-Fiction' ? 'selected' : '' ?>>Non-Fiction</option>
        </select>
        
        <select name="language">
            <option value="">All Languages</option>
            <option value="English" <?= $language == 'English' ? 'selected' : '' ?>>English</option>
            <option value="Arabic" <?= $language == 'Arabic' ? 'selected' : '' ?>>Arabic</option>
        </select>

        <select name="status">
            <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>

        <button type="submit">🔍 Search</button>
    </form>

    <!-- Display Books -->
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Language</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if (count($books) > 0): ?>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><a href="book_details.php?id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></a></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['category']) ?></td>
                    <td><?= htmlspecialchars($book['language']) ?></td>
                    <td><?= htmlspecialchars($book['status']) ?></td>
                    <td><a href="book_details.php?id=<?= $book['id'] ?>">View Details</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No books found. Try adjusting your search or filters.</td></tr>
        <?php endif; ?>
    </table>
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
