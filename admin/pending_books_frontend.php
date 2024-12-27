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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-white text-2xl mr-3"></i>
                    <span class="text-white text-lg font-semibold">Admin Panel</span>
                </div>
                <div>
                    <a href="../dashboard.php" class="text-white hover:text-indigo-200 transition">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pending Books Review</h1>
            <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold">
                <?= count($books) ?> Books Pending Review
            </span>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($books as $book): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-book text-gray-400 mr-3"></i>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($book['title']) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?= htmlspecialchars($book['author']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" class="flex space-x-2">
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button type="submit" name="action" value="approved" 
                                                class="bg-green-100 text-green-800 px-4 py-2 rounded hover:bg-green-200 transition">
                                            <i class="fas fa-check mr-2"></i>Approve
                                        </button>
                                        <button type="submit" name="action" value="rejected"
                                                class="bg-red-100 text-red-800 px-4 py-2 rounded hover:bg-red-200 transition">
                                            <i class="fas fa-times mr-2"></i>Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>