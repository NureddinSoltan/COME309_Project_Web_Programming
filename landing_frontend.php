<?php

require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$language = $_GET['language'] ?? '';

$categories = $conn->query("SELECT DISTINCT category FROM books WHERE status = 'approved'")->fetchAll(PDO::FETCH_COLUMN);
$languages = $conn->query("SELECT DISTINCT language FROM books WHERE status = 'approved'")->fetchAll(PDO::FETCH_COLUMN);

$query = "SELECT * FROM books WHERE status = 'approved'";
$params = [];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

if (!empty($language)) {
    $query .= " AND language = ?";
    $params[] = $language;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Explore Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="assets/css/includes/header.css">

</head>
<body>


    <div class="container">
        <header class="page-header">
            <h1><i class="fas fa-books"></i> Explore Our Collection</h1>
            <p class="subtitle">Discover amazing books in our library</p>
        </header>

        <section class="search-section">
            <form method="GET" class="search-form">
                <div class="search-group">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" 
                               name="search" 
                               placeholder="Search by Title or Author" 
                               value="<?= htmlspecialchars($search) ?>"
                               class="search-input">
                    </div>

                    <div class="filter-group">
                        <select name="category" class="filter-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" 
                                        <?= $category == $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="language" class="filter-select">
                            <option value="">All Languages</option>
                            <?php foreach ($languages as $lang): ?>
                                <option value="<?= htmlspecialchars($lang) ?>" 
                                        <?= $language == $lang ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lang) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="landing_frontend.php" class="clear-button">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </section>

        <section class="books-section">
            <?php if (!empty($books)): ?>
                <div class="book-grid">
                    <?php foreach ($books as $book): ?>
                        <article class="book-card">
                            <div class="book-cover">
                                <img src="<?= htmlspecialchars($book['book_image']) ?>" 
                                     alt="Cover of <?= htmlspecialchars($book['title']) ?>">
                            </div>
                            <div class="book-info">
                                <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                                <div class="book-details">
                                    <p class="author">
                                        <i class="fas fa-user"></i>
                                        <?= htmlspecialchars($book['author']) ?>
                                    </p>
                                    <p class="category">
                                        <i class="fas fa-bookmark"></i>
                                        <?= htmlspecialchars($book['category']) ?>
                                    </p>
                                    <p class="language">
                                        <i class="fas fa-globe"></i>
                                        <?= htmlspecialchars($book['language']) ?>
                                    </p>
                                </div>
                                <a href="book_details.php?id=<?= $book['id'] ?>" class="view-details-btn">
                                    <i class="fas fa-info-circle"></i> View Details
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>No books found. Try adjusting your search or filters.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>