<?php
session_start();
require 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch();

    if ($user) {
        $otp = rand(1000, 9999); // Generate 4-digit OTP
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send email
        $subject = "Password Reset OTP";
        $message = "Your OTP for password reset is: $otp";
        $headers = "From: no-reply@yourwebsite.com";
        mail($email, $subject, $message, $headers);

        header("Location: otp.php");
        exit();
    } else {
        echo "<script>alert('Email not found!'); window.location.href='forgotpassword.php';</script>";
    }
}
?>
