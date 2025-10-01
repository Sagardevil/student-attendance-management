<?php
// Enable error reporting for debugging (optional)
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Database configuration
    $host = 'localhost';            // MySQL host
    $dbname = 'attendance_system';  // Your database name
    $username = 'root';             // MySQL username (default for XAMPP)
    $password = '';                 // MySQL password (default empty in XAMPP)

    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set PDO error mode to Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // If connection fails, show error and exit
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]));
}
?>

