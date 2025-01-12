<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

// Default Search and Filter Values, If not provided, defaults to an empty string.
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$language = $_GET['language'] ?? '';
$status = $_GET['status'] ?? '';
$min_pages = $_GET['min_pages'] ?? '';
$max_pages = $_GET['max_pages'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

// Fetch distinct categories and languages
$categories = $conn->query("SELECT DISTINCT category FROM books")->fetchAll(PDO::FETCH_COLUMN);
$languages = $conn->query("SELECT DISTINCT language FROM books")->fetchAll(PDO::FETCH_COLUMN);

// Base Query to retrieves all books with the status approved.
$query = "SELECT * FROM books WHERE status = 'approved'";
$params = []; // an array to hold query parameters for prepared statements.

// Search by Title or Author
if (!empty($search)) {
    $query .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Filter by Category
if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

// Filter by Language
if (!empty($language)) {
    $query .= " AND language = ?";
    $params[] = $language;
}

// Filter by Status (Only for Admins)
if ($_SESSION['user_role'] === 'admin' && !empty($status)) {
    $query .= " AND status = ?";
    $params[] = $status;
}

// Filter by Pages Range
if (!empty($min_pages) && is_numeric($min_pages)) {
    $query .= " AND pages >= ?";
    $params[] = $min_pages;
}
if (!empty($max_pages) && is_numeric($max_pages)) {
    $query .= " AND pages <= ?";
    $params[] = $max_pages;
}

// Filter by Price Range
if (!empty($min_price) && is_numeric($min_price)) {
    $query .= " AND price >= ?";
    $params[] = $min_price;
}
if (!empty($max_price) && is_numeric($max_price)) {
    $query .= " AND price <= ?";
    $params[] = $max_price;
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
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="books-container">
        <h2>📚 Browse Books</h2>

        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <input type="text" name="search" placeholder="Search by Title or Author" value="<?= htmlspecialchars($search) ?>">

            <!-- Dynamic Category Dropdown -->
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $category == $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Dynamic Language Dropdown -->
            <select name="language">
                <option value="">All Languages</option>
                <?php foreach ($languages as $lang): ?>
                    <option value="<?= htmlspecialchars($lang) ?>" <?= $language == $lang ? 'selected' : '' ?>>
                        <?= htmlspecialchars($lang) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Status Filter (Only for Admins) -->
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            <?php endif; ?>

            <!-- Pages and Price Range -->
            <input type="number" name="min_pages" placeholder="Min Pages" value="<?= htmlspecialchars($min_pages) ?>">
            <input type="number" name="max_pages" placeholder="Max Pages" value="<?= htmlspecialchars($max_pages) ?>">
            <input type="number" name="min_price" placeholder="Min Price" value="<?= htmlspecialchars($min_price) ?>">
            <input type="number" name="max_price" placeholder="Max Price" value="<?= htmlspecialchars($max_price) ?>">

            <button type="submit">🔍 Search</button>
            <a href="books_frontend.php"><button type="button">Clear</button></a>
        </form>

        <!-- Display Books -->
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Language</th>
                <th>Pages</th>
                <th>Price</th>
            </tr>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['category']) ?></td>
                        <td><?= htmlspecialchars($book['language']) ?></td>
                        <td><?= htmlspecialchars($book['pages']) ?></td>
                        <td>$<?= htmlspecialchars($book['price']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No books found. Try adjusting your search or filters.</td>
                </tr>
            <?php endif; ?>
        </table>
        <a href="dashboard_frontend.php">⬅️ Back to Dashboard</a>
</body>

</html>