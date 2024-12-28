<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

// Admin Stats
if ($_SESSION['user_role'] === 'admin') {
    $total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $total_books = $conn->query("SELECT COUNT(*) FROM books")->fetchColumn();
    $pending_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'pending'")->fetchColumn();
    $approved_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'approved'")->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stats div {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 200px;
        }

        .stats div h3 {
            margin: 0;
        }

        a {
            display: block;
            margin: 10px 0;
        }

        button {
            padding: 10px;
            margin-top: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }
    </style>
</head>

<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

    <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <h3>Admin Dashboard</h3>
        <div class="stats">
            <div>
                <h3>Total Users</h3>
                <p><?= $total_users ?></p>
            </div>
            <div>
                <h3>Total Books</h3>
                <p><?= $total_books ?></p>
            </div>
            <div>
                <h3>Pending Books</h3>
                <p><?= $pending_books ?></p>
            </div>
            <div>
                <h3>Approved Books</h3>
                <p><?= $approved_books ?></p>
            </div>
            <?php
            $rejected_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'rejected'")->fetchColumn();
            ?>
            <div>
                <h3>Rejected Books</h3>
                <p><?= $rejected_books ?></p>
            </div>
        </div>
        <a href="admin/manage_users.php"><button>Manage Users</button></a>
        <a href="admin/pending_books.php"><button>Manage Pending Books</button></a>
        <a href="admin/manage_comments.php"><button>Manage Comments</button></a>
        <a href="admin/manage_users.php"><button>Manage Users</button></a>
        <a href="admin/manage_books.php?status=rejected"><button>View Rejected Books</button></a>

    <?php else: ?>
        <h3>User Dashboard</h3>
        <a href="user/my_books.php"><button>My Books</button></a>
        <a href="user/upload_book.php"><button>Upload New Book</button></a>
        <a href="books.php"><button>Browse Books</button></a>
    <?php endif; ?>

    <a href="logout.php"><button style="background: #e74c3c;">Logout</button></a>
</body>

</html>