<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $_SESSION['email'] = $email; // Store email in session

    // Generate a 4-digit OTP
    $otp = rand(1000, 9999);
    $_SESSION['otp'] = $otp; // Store OTP in session

    // PHPMailer setup
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'delacruzjefrey15@gmail.com'; // Your Gmail address
        $mail->Password = 'mawy izex lpsh ymkt'; // Your Gmail App Password (Not your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email setup
        $mail->setFrom('Salon@gmail.com', 'FROM DIVAS SALON '); // Sender's email
        $mail->addAddress($email); // Recipient's email
        $mail->Subject = "OTP Code For Password Reset";
        $mail->Body = "Your OTP for password reset is: " . $otp;
        $mail->isHTML(false);

        if ($mail->send()) {
            echo "<script>
                    alert('OTP sent successfully! Redirecting to OTP verification...');
                    window.location.href = 'otp.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Failed to send OTP. Please try again.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error sending OTP: " . $mail->ErrorInfo . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password?</title>
    <link rel="stylesheet" type="text/css" href="forgotpass.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password?</h2>
        <p>Enter your email address to reset your password.</p>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <br>
            <button type="submit">Next</button>
        </form>
    </div>
</body>
</html>
