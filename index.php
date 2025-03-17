<?php
session_start();
include('./db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error = '';

    if (empty($identifier) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        // Student Login
        $stmt = $pdo->prepare("SELECT * FROM student_login WHERE email = :identifier");
        $stmt->execute(['identifier' => $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['student_id'];
            header("Location: ./student");
            exit;
        }

        // Teacher Login
        $stmt = $pdo->prepare("SELECT * FROM teacher_login WHERE email = :identifier");
        $stmt->execute(['identifier' => $identifier]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($teacher && password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['teacher_id'];
            header("Location: ./teacher");
            exit;
        }

        // Admin Login
        $stmt = $pdo->prepare("SELECT * FROM admin_login WHERE email = :identifier");
        $stmt->execute(['identifier' => $identifier]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: ./admin");
            exit;
        }

        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="login-box">
        <div class="logo">
            <img src="./images/ITE.png" alt="ITE Logo">
        </div>
        <h2>ITE Login</h2>

        <form method="POST" action="">
            <p align="center"><?php if (isset($_GET['success'])) {
                                    echo '<div style="color: green;">' . $_GET['success'] . '</div>';
                                } elseif (isset($_GET['error'])) {
                                    echo '<div style="color: red;">' . $_GET['error'] . '</div>';
                                } ?></p>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit">Login</button>
            <p class="signup-link"><a href="signup.php">Create an account</a></p>
            <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        </form>
    </div>
    
</div>

</body>
</html>


</html>
<style>
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

/* Full-Screen Background */
body {
       background: #6E1313;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* Container to Center the Login Box */
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

/* Glassmorphism Login Box */
.login-box {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(15px);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    width: 360px;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    position: relative;
}

/* Logo Styling */
.logo img {
    width: 90px; 
    height: 90px;
    border-radius: 50%; 
    object-fit: cover;
    display: block;
    margin: -50px auto 10px; /* Moves logo slightly outside the box */
    border: 4px solid white; 
    background: rgba(255, 255, 255, 0.2);
}

/* Title */
h2 {
    color: black;
    margin-bottom: 15px;
    font-size: 22px;
}

/* Input Fields */
.form-group {
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    display: block;
    color: black;
    font-size: 14px;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    background: rgba(255, 255, 255, 0.2);
    color: black;
    outline: none;
    transition: 0.3s;
}

.form-group input::placeholder {
    color: black;
}

.form-group input:focus {
    background: rgba(255, 255, 255, 0.4);
}

/* Login Button */
button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #ff3d00;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
}

button:hover {
    background: #e63a00;
}

/* Signup Link */
.signup-link {
    margin-top: 10px;
    font-size: 14px;
}

.signup-link a {
    color: #ffcccb;
    font-weight: bold;
    text-decoration: none;
}

.signup-link a:hover {
    text-decoration: underline;
}
.message {
    text-align: center;
    font-weight: bold;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
/* Responsive Design */
@media (max-width: 400px) {
    .login-box {
        width: 90%;
        padding: 20px;
    }

    .form-group input {
        font-size: 14px;
        padding: 10px;
    }

    button {
        font-size: 14px;
        padding: 10px;
    }
}

</style>