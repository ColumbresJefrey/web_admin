<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "No email found. Try resetting your password again.";
    exit();
}

$otp = rand(1000, 9999);
$_SESSION['otp'] = $otp;

$to = $_SESSION['email'];
$subject = "Your New OTP Code";
$message = "Your new OTP is: " . $otp;
$headers = "From: no-reply@yourwebsite.com";

if (mail($to, $subject, $message, $headers)) {
    header("Location: otp.php");
    exit();
} else {
    echo "Failed to resend OTP.";
}
?>
