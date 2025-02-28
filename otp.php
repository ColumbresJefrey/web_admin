<<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
        // OTP is correct
        unset($_SESSION['otp']); // Remove OTP from session
        $_SESSION['otp_verified'] = true; // Set session flag for verified user
        header("Location: reset_password.php"); // Redirect to password reset
        exit();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="otp.css">
</head>
<body>
    <div class="container">
        <h2>Account Verification</h2>
        <p>We just sent a code to your email. Please enter the code to verify your account.</p>

        <form action="verify_otp.php" method="POST">
            <div class="otp-inputs">
                <input type="text" name="otp1" maxlength="1" onkeyup="moveToNext(this, 'otp2')">
                <input type="text" name="otp2" maxlength="1" onkeyup="moveToNext(this, 'otp3')">
                <input type="text" name="otp3" maxlength="1" onkeyup="moveToNext(this, 'otp4')">
                <input type="text" name="otp4" maxlength="1">
            </div>
            <input type="hidden" name="otp" id="otp">
            <button type="submit">Verify</button>
        </form>

        <p class="resend-text">Did not get the code? 
            <a href="resend_otp.php"><span>Resend code</span></a>
        </p>
    </div>
    <script>
        function moveToNext(current, nextFieldID) {
            if (current.value.length === 1) {
                document.getElementsByName(nextFieldID)[0].focus();
            }
        }

        // Combine OTP inputs into one hidden field before form submission
        document.querySelector("form").addEventListener("submit", function () {
            let otp = "";
            document.querySelectorAll(".otp-inputs input").forEach(input => {
                otp += input.value;
            });
            document.getElementById("otp").value = otp;
        });
    </script>
</body>
</html>
