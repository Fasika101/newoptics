<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'mail.otherdomain.com'; // Your working SMTP host
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@otherdomain.com';
    $mail->Password   = 'password_here';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Form data received from Hosting A
    $name    = $_POST['name'] ?? 'Unknown';
    $email   = $_POST['email'] ?? 'noemail@domain.com';
    $message = $_POST['message'] ?? 'No message';

    $mail->setFrom('info@otherdomain.com', 'Relay Server');
    $mail->addAddress('your@email.com');

    $mail->isHTML(true);
    $mail->Subject = "New Contact Form from $name";
    $mail->Body    = "Name: $name<br>Email: $email<br>Message:<br>$message";

    $mail->send();
    echo '✅ Message sent successfully from relay server';
} catch (Exception $e) {
    echo "❌ Relay failed. Error: {$mail->ErrorInfo}";
}
