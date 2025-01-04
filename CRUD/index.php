<?php
require_once 'pdo.php';  // 引入数据库连接
session_start();

// 处理表单提交操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) { // 增加用户
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
        } else {
            // 插入新用户
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "User added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add user.";
            }
        }
    } elseif (isset($_POST['update'])) { // 修改用户
        $id = $_POST['id'];  // 更新为使用 'id' 而不是 'user_id'
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
        } else {
            // 更新用户数据
            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':id', $id);  // 更新为使用 'id' 而不是 'user_id'

            if ($stmt->execute()) {
                $_SESSION['success'] = "User updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update user.";
            }
        }
    } elseif (isset($_POST['delete'])) { // 删除用户
        $id = $_POST['id'];  // 更新为使用 'id' 而不是 'user_id'

        // 删除用户
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);  // 更新为使用 'id' 而不是 'user_id'
        if ($stmt->execute()) {
            $_SESSION['success'] = "User deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete user.";
        }
    }
}

// 查询所有用户
$stmt = $conn->query("SELECT id, username, email, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #e9e9e9;
        }
        a {
            color: blue;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>User Management</h2>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
?>

<!-- 增加用户表单 -->
<h3>Add User</h3>
<form method="POST">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit" name="add">Add User</button>
</form>

<hr>

<!-- 修改用户表单 -->
<h3>Edit User</h3>
<form method="POST">
    <label for="id">User ID:</label><br>
    <input type="text" id="id" name="id" required><br><br>

    <label for="edit_username">Username:</label><br>
    <input type="text" id="edit_username" name="username" required><br><br>

    <label for="edit_email">Email:</label><br>
    <input type="email" id="edit_email" name="email" required><br><br>

    <label for="edit_password">Password:</label><br>
    <input type="password" id="edit_password" name="password" required><br><br>

    <button type="submit" name="update">Update User</button>
</form>

<hr>

<!-- 删除用户表单 -->
<h3>Delete User</h3>
<form method="POST">
    <label for="delete_id">User ID:</label><br>
    <input type="text" id="delete_id" name="id" required><br><br>

    <button type="submit" name="delete">Delete User</button>
</form>
<!--  -->
<hr>

<!-- 用户列表 -->
<h3>Users List</h3>
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
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlentities($user['username']); ?></td>
                <td><?php echo htmlentities($user['email']); ?></td>
                <td><?php echo htmlentities($user['password']); ?></td>
                <td>
                    <a href="index.php?edit_id=<?php echo $user['id']; ?>">Edit</a> |
                    <a href="index.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
