<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }
    
    if (!$email) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }
    
    // Email configuration
    $to = "hello@udoragroup.com";
    $subject = "New Contact Form Submission from " . $name;
    
    // Email body
    $email_body = "You have received a new contact form submission from your website.\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Message:\n" . $message . "\n\n";
    $email_body .= "---\n";
    $email_body .= "Sent from: " . $_SERVER['HTTP_HOST'] . "\n";
    $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $email_body .= "Date: " . date('Y-m-d H:i:s') . "\n";
    
    // Email headers
    $headers = array();
    $headers[] = "From: \"Website Contact Form\" <noreply@udoragroup.com>";
    $headers[] = "Reply-To: " . $email;
    $headers[] = "Return-Path: noreply@udoragroup.com";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    $headers[] = "Content-Type: text/plain; charset=UTF-8";
    
    // Send email
    if (mail($to, $subject, $email_body, implode("\r\n", $headers))) {
        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again or contact us directly at hello@udoragroup.com.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
?>
