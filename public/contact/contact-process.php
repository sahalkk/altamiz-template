<?php
/**
 * Contact Form Handler
 * 
 * CONFIGURATION:
 * Update the $to variable below with your receiving email address
 * 
 * NOTE: This script requires a properly configured mail server.
 * For local testing, you may need to configure PHP mail() or use a mail service.
 */

// Configuration
$receiving_email = "msahalkk357@gmail.com"; // CHANGE THIS to your receiving email

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
    
    // Send email
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo json_encode(array("success" => true, "message" => "Thank you! We will contact you soon."));
    } else {
        echo json_encode(array("success" => false, "message" => "Sorry, there was an error sending your message. Please try again."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}
?>

