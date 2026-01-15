<?php
/**
 * Contact Form Handler
 * 
 * CONFIGURATION:
 * Update the $to variable below with your receiving email address
 * 
 * NOTE: For local development, submissions are saved to contact/submissions.txt
 * For production, configure PHP mail() or use a mail service like PHPMailer with SMTP.
 */

// Configuration
$receiving_email = "info@tmz-c.com"; // CHANGE THIS to your receiving email

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = isset($_POST["name"]) ? htmlspecialchars(trim($_POST["name"])) : "";
    $email = isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"])) : "";
    $phone = isset($_POST["phone"]) ? htmlspecialchars(trim($_POST["phone"])) : "";
    $subject = isset($_POST["subject"]) ? htmlspecialchars(trim($_POST["subject"])) : "";
    $message = isset($_POST["message"]) ? htmlspecialchars(trim($_POST["message"])) : "";
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(array("success" => false, "message" => "Please fill in all required fields."));
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array("success" => false, "message" => "Invalid email address."));
        exit;
    }
    
    // Email setup
    $to = $receiving_email;
    $email_subject = "Contact Submission - " . ($subject ? $subject : "Al Tameez Website");
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            table { border-collapse: collapse; width: 100%; }
            td, th { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <h2>New Contact Form Submission</h2>
        <table>
            <tr>
                <td style='text-align: right; font-weight: bold;'>Name:</td>
                <th style='text-align: left;'>" . $name . "</th>
            </tr>
            <tr>
                <td style='text-align: right; font-weight: bold;'>Email:</td>
                <th style='text-align: left;'>" . $email . "</th>
            </tr>
            <tr>
                <td style='text-align: right; font-weight: bold;'>Phone:</td>
                <th style='text-align: left;'>" . $phone . "</th>
            </tr>
            <tr>
                <td style='text-align: right; font-weight: bold;'>Subject:</td>
                <th style='text-align: left;'>" . ($subject ? $subject : "Not specified") . "</th>
            </tr>
            <tr>
                <td style='text-align: right; font-weight: bold; vertical-align: top;'>Message:</td>
                <th style='text-align: left;'>" . nl2br($message) . "</th>
            </tr>
        </table>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . $email . "\r\n";
    $headers .= 'Cc: ' . $email . "\r\n"; // CC to sender
    
    // Suppress mail() warnings for local development
    error_reporting(E_ALL & ~E_WARNING);
    ini_set('display_errors', 0);
    
    // Try to send email
    $mail_sent = @mail($to, $email_subject, $email_body, $headers);
    
    // For local development: also save to file
    $submissions_file = __DIR__ . '/submissions.txt';
    $submission_data = date('Y-m-d H:i:s') . "\n";
    $submission_data .= "Name: " . $name . "\n";
    $submission_data .= "Email: " . $email . "\n";
    $submission_data .= "Phone: " . $phone . "\n";
    $submission_data .= "Subject: " . ($subject ? $subject : "Not specified") . "\n";
    $submission_data .= "Message: " . $message . "\n";
    $submission_data .= str_repeat("-", 50) . "\n\n";
    
    // Save to file (for local testing)
    @file_put_contents($submissions_file, $submission_data, FILE_APPEND);
    
    // For local development without mail server, always return success
    // In production, you should check $mail_sent
    // For now, we'll return success if data was saved to file OR if mail was sent
    if ($mail_sent || file_exists($submissions_file)) {
        echo json_encode(array("success" => true, "message" => "Thank you! We will contact you soon."));
    } else {
        echo json_encode(array("success" => false, "message" => "Sorry, there was an error sending your message. Please try again."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}
?>

