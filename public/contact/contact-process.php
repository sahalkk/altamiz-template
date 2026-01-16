<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$mail = new PHPMailer(true);

try {
    // GoDaddy SMTP (Titan)
    $mail->isSMTP();
    $mail->Host       = 'localhost';
    $mail->Port       = 25;
    $mail->SMTPAuth   = false;
    $mail->SMTPSecure = false;
    $mail->Username   = '';     // YOUR EMAIL
    $mail->Password   = '';     // EMAIL PASSWORD

    $mail->setFrom('info@tmz-c.com', 'Al Tameez Website');
    $mail->addAddress('info@tmz-c.com');
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $name . " - TMZ Website Enquiry";
    $mail->Body = "
        <h2>Contact Form Enquiry</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Category:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
    ";
    $mail->send();
    echo json_encode(["success" => true, "message" => "Message sent successfully"]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to send message: " . $mail->ErrorInfo
    ]);
    exit;
}    
 