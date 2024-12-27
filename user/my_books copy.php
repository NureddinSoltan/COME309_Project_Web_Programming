<?php
require '../includes/auth.php';
require '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user's books
$stmt = $conn->prepare("SELECT * FROM books WHERE uploaded_by = ?");
$stmt->execute([$user_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books | Digital Library</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-book-reader text-white text-2xl mr-3"></i>
                    <span class="text-white text-lg font-semibold">Digital Library</span>
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
            <h1 class="text-3xl font-bold text-gray-900">My Books Collection</h1>
            <a href="upload_book.php" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>Upload New Book
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="fas fa-books text-indigo-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Books</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $stmt->rowCount(); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Published</h3>
                        <p class="text-2xl font-semibold text-gray-900">
                            <?php 
                            $published = $conn->prepare("SELECT COUNT(*) FROM books WHERE uploaded_by = ? AND status = 'published'");
                            $published->execute([$user_id]);
                            echo $published->fetchColumn();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Pending</h3>
                        <p class="text-2xl font-semibold text-gray-900">
                            <?php 
                            $pending = $conn->prepare("SELECT COUNT(*) FROM books WHERE uploaded_by = ? AND status = 'pending'");
                            $pending->execute([$user_id]);
                            echo $pending->fetchColumn();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($books as $book): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-book text-gray-400 mr-3"></i>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($book['title']) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $book['status'] === 'published' 
                                            ? 'bg-green-100 text-green-800' 
                                            : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= htmlspecialchars($book['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="edit_book.php?id=<?= $book['id'] ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-4">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <a href="#" onclick="confirmDelete(<?= $book['id'] ?>)" 
                                       class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt mr-1"></i>Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(bookId) {
        if (confirm('Are you sure you want to delete this book?')) {
            window.location.href = `delete_book.php?id=${bookId}`;
        }
    }
    </script>
</body>
</html>