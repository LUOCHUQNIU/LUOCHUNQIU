<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'sse3150';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['user'] ?? '';
$pass = $data['pass'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $user);
    $stmt->bindParam(':password', $pass);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Login success - return success and redirect URL
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful, welcome back ' . $user,
            'redirect' => 'login-success.html?username=' . urlencode($user)  // Provide the URL to redirect to
        ]);
    } else {
        // Login failed
        echo json_encode(['success' => false, 'message' => 'Login failed. Invalid username or password.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Query failed: ' . $e->getMessage()]);
}
