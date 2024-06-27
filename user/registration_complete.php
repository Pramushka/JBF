<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

// Include PHPMailer files
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// End the session and send email when user clicks the login button
if (isset($_POST['logout'])) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                      // Disable verbose debug output
        $mail->isSMTP();                           // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';      // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                  // Enable SMTP authentication
        $mail->Username   = 'graphics2@emeraldisle.lk';                     //SMTP username
        $mail->Password   = 'ecfsnxzilkprqyol';      // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
        $mail->Port       = 465;                   // TCP port to connect to

        // Recipients
        $mail->setFrom('graphics2@emeraldisle.lk', 'Job Force');
        $mail->addAddress($_SESSION['user_email']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Registering with Job Force';
        $mail->Body    = '<p>Dear user,</p><p>Thank you for registering with Job Force. We are excited to have you on board.</p><p>Best regards,<br>Job Force Team</p>';
        $mail->AltBody = "Dear user,\n\nThank you for registering with Job Force. We are excited to have you on board.\n\nBest regards,\nJob Force Team";

        $mail->send();

        // Email sent successfully, end the session
        session_destroy();
        header("Location: login.php");
        exit;
    } catch (Exception $e) {
        $error_message = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Complete - Job Force</title>
    <link rel="stylesheet" href="../assets/css/kp.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f2ef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #333;
            font-size: 1.8em;
            font-weight: bold;
        }

        p {
            color: #666;
            font-size: 1em;
            margin: 20px 0;
        }

        .btn {
            background-color: #0073b1;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
            margin: 20px 0 10px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #005582;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thank You for Registering!</h2>
        <p>Thank you for registering with us. Click the button below to log in.</p>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <button type="submit" name="logout" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
