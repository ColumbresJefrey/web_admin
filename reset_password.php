<?php
// Enable strict error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

// Debugging: Check the request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request. Please submit the form.");
}

// Check if admin session exists
if (!isset($_SESSION['admin_username'])) {
    die("Session expired. Please log in again.");
}

// Get username from session
$username = $_SESSION['admin_username'];

// Get new password from form input
$new_password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate password fields
if (empty($new_password) || empty($confirm_password)) {
    die("Both password fields are required.");
}

if ($new_password !== $confirm_password) {
    die("Passwords do not match.");
}

// Include database configuration
$config_path = __DIR__ . '/config.php';
if (!file_exists($config_path)) {
    die("Configuration file not found.");
}
include $config_path;

// Ensure database connection exists
if (!isset($conn) || $conn->connect_error) {
    die("Database connection error.");
}

// Update password in admin table (without hashing)
$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE admin_username = ?");
$stmt->bind_param("ss", $new_password, $username);
$stmt->execute();

// Check if the password was updated
if ($stmt->affected_rows > 0) {
    echo "Password reset successful. Please log in with your new password.";
} else {
    echo "Error: Password was not updated. Please try again.";
}

// Close the database connection
$stmt->close();
$conn->close();

// Destroy session after password reset
unset($_SESSION['admin_username']);
session_destroy();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reset Password</title>
    <style>
        body {
            background-image: url("loginbackground.png");
            background-size: cover;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: black;
        }

        label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            display: none;
        }

        button {
            background: black;
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: yellow;
            color: black;
            transform: scale(1.05);
        }
    </style>
    <script>
        function validateForm() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            let errorMessage = document.getElementById("error-message");

            if (password !== confirmPassword) {
                errorMessage.style.display = "block";
                return false;
            } else {
                errorMessage.style.display = "none";
                return true;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Reset Admin Password</h2>
        <form action="reset_password.php" method="POST" onsubmit="return validateForm()">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <p class="error-message" id="error-message">Passwords do not match!</p>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
