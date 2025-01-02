<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Default Search and Filter Values
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$language = $_GET['language'] ?? '';

// Fetch distinct categories and languages
$categories = $conn->query("SELECT DISTINCT category FROM books WHERE status = 'approved'")->fetchAll(PDO::FETCH_COLUMN);
$languages = $conn->query("SELECT DISTINCT language FROM books WHERE status = 'approved'")->fetchAll(PDO::FETCH_COLUMN);

// Base Query
$query = "SELECT * FROM books WHERE status = 'approved'";
$params = [];

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

// Execute Query
$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library - Explore Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/includes/header.css">

    <style>
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filter-form input,
        .filter-form select {
            padding: 5px;
            border-radius: 5px;
        }
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        .book-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            padding: 10px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .book-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .book-card h3 {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>🌟 Explore Our Books</h2>

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

        <button type="submit">🔍 Search</button>
        <a href="landing.php"><button type="button">Clear</button></a>
    </form>

    <!-- Display Books -->
    <div class="book-grid">
        <?php if (!empty($books)): ?>
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img src="<?= htmlspecialchars($book['book_image']) ?>" alt="Book Cover">
                    <h3><?= htmlspecialchars($book['title']) ?></h3>
                    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
                    <p><strong>Language:</strong> <?= htmlspecialchars($book['language']) ?></p>
                    <a href="book_details.php?id=<?= $book['id'] ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No books found. Try adjusting your search or filters.</p>
        <?php endif; ?>
    </div>
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
