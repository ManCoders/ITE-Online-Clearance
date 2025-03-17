<?php
session_start();
include('db_connect.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid
    $sql = "SELECT id FROM students WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $update_sql = "UPDATE students SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?";
            $update_stmt = $mysqli->prepare($update_sql);
            $update_stmt->bind_param("si", $new_password, $id);
            $update_stmt->execute();

            echo "Password reset successful! <a href='login.php'>Login</a>";
            exit();
        }
    } else {
        echo "Invalid or expired token.";
        exit();
    }
} else {
    echo "No reset token provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>

<div class="container">
    <form method="POST" action="">
        <label for="password">New Password:</label>
        <input type="password" name="password" placeholder="Enter new password" required>
        <button type="submit">Reset Password</button>
    </form>
</div>

</body>
</html>
