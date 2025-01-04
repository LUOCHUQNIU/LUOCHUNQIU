<?php
// PDO Database connection settings
$host = 'localhost';       // Your database host
$dbname = 'sse3150';       // Your database name
$username = 'root';        // Your database username
$password = '';            // Your database password (default for localhost is usually empty)

// Set up the PDO options for error handling and charset
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Enable exception handling for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES => false,  // Disable emulation of prepared statements (better security)
];

// Create a new PDO instance and handle connection errors
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    // If connection fails, display an error message and stop the script
    die("Connection failed: " . $e->getMessage());
}
?>
