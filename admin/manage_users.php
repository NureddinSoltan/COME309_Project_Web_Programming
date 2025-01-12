<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/header.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Handle form submission for adding a new user
if (isset($_POST['add_user'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);
        echo "✅ User added successfully!";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

// Handle form submission for deleting a user
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']); // Get the user ID and convert it to an integer
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    echo "✅ User deleted successfully!";
}

// Fetch All Users
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all users as an associative array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/admin/manage_users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <div class="manage-users-container">
        <h2>👤 Manage Users</h2>
        <!-- Add User Form -->
        <h3>Add User</h3>
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label>Role:</label>
                <select name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="add_user">Add User</button>
        </form>

        <!-- User List -->
        <h3>All Users</h3>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="delete_user" onclick="return confirm('Are you sure?')">❌ Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="../dashboard.php">⬅️ Back to Dashboard</a>
</body>

</html>