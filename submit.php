<?php
// Set up error reporting for debugging during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Honeypot field check
if (!isset($_POST['url']) || !empty($_POST['url'])) {
    // Redirect bots away gracefully
    header('Location: /');
    exit();
}

// Check if all required fields are set
if (!isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    header('Location: /');
    exit();
}

// Sanitize user input
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
// Assuming a 'phone' field from the form
$phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Handle validation failure gracefully
    header('Location: /');
    exit();
}

// Your email address (the recipient)
$youremail = "blrichardson2993@gmail.com";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings for authenticated SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $youremail;
    $mail->Password = 'lvqt bsqb brhs qzrf'; // Use your secure app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom($youremail, 'Contact Form Submission');
    $mail->addAddress($youremail);

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'Contact Form: ' . $name;
    $mail->Body = "You have received a new message from your website contact form.\n\n" .
                  "Name: $name\n" .
                  "E-Mail: $email\n" .
                  "Phone: $phone\n" .
                  "Message:\n$message";

    // Set the Reply-To header
    $mail->addReplyTo($email, $name);


    $mail->send();
    header('Location: thank_you.php');
    exit();
} catch (Exception $e) {
    echo "Mailer Error: " . $e->getMessage();
    // Remove header('Location: /'); and exit(); for now.
}

?>