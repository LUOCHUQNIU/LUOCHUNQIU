<?php
require_once 'pdo.php';
session_start();

// 查询所有用户
$users = [];
try {
    $stmt = $conn->query("SELECT id, username, email, password FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    $_SESSION['error'] = "Database query failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
</head>
<body>

<h2>User Management</h2>

<!-- 用户列表 -->
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users) && is_array($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlentities($user['username']); ?></td>
                    <td><?= htmlentities($user['email']); ?></td>
                    <td><?= htmlentities($user['password']); ?></td>
                    <td>
                        <a href="index.php?edit_id=<?= $user['id']; ?>">Edit</a> |
                        <a href="index.php?delete_id=<?= $user['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
