<?php
ob_start();
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$dbname = 'sse3150';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('Location: register-success.html?success=false&message=' . urlencode('Connection failed: ' . $e->getMessage()));
    exit();
}

// Retrieve and sanitize input data
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$email = trim($_POST['email'] ?? '');
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');

if (empty($username) || empty($password) || empty($email) || empty($firstname) || empty($lastname)) {
    header('Location: register-success.html?success=false&message=' . urlencode('All fields are required.'));
    exit();
}

// Encrypt the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, firstname, lastname) VALUES (:username, :password, :email, :firstname, :lastname)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->execute();

    // Redirect to the success page with success message
    header('Location: register-success.html?success=true&message=' . urlencode('Registration successful! You can now login.'));
    exit();

} catch (PDOException $e) {
    $errorMessage = ($e->getCode() === '23000') 
        ? 'Username or email already exists.' 
        : 'Registration failed: ' . $e->getMessage();
    header('Location: register-success.html?success=false&message=' . urlencode($errorMessage));
    exit();
}
?>
