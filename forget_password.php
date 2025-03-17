<?php
session_start();
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_input = $_POST['login_input']; // Email or phone
    $token = bin2hex(random_bytes(50)); // Generate unique token
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

    // Check if user exists
    $sql = "SELECT id, email FROM students WHERE email = ? OR phone = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $email);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        // Store the reset token
        $update_sql = "UPDATE students SET reset_token = ?, reset_token_expiry = ? WHERE id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bind_param("ssi", $token, $expiry, $id);
        $update_stmt->execute();

        // Send email (or SMS)
        $reset_link = "http://localhost/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: $reset_link";
        mail($email, $subject, $message, "From: no-reply@yourwebsite.com");

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with this email or phone.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./style/login.css">
</head>
<body>

<div class="container">
    <form method="POST" action="">
        <label for="login_input">Email or Phone:</label>
        <input type="text" name="login_input" placeholder="Enter email or phone" required>
        <button type="submit">Send Reset Link</button>
        <a href="index.php">Back to Login</a>
    </form>
</div>

</body>
</html>
