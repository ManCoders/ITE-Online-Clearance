<?php
session_start();
include "../db_connect.php"; // Ensure this contains the correct PDO connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $studentId = trim($_POST['studentId']);
    $fname = trim($_POST['fname']);
    $mname = trim($_POST['mname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Error handling
    $_SESSION['error'] = "";

    if (empty($studentId) || empty($fname) || empty($mname) || empty($lname) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
    } else {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO admins (admin_code, fname, lname, mname, contact) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$studentId, $fname, $lname, $mname, $contact]);

            $stmt = $pdo->prepare("SELECT id FROM admins");
            $stmt->execute();
            $admin_id = $stmt->fetchColumn();

            $stmt = $pdo->prepare("INSERT INTO admin_login (admin_id, email, password) VALUES (?,?,?)");
            $stmt->execute([$admin_id, $email, $hashed_password]);

            $_SESSION["success"] = " Registration successful";
            header("Location: ../index.php");
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }

    header("Location: signup.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITE Signup</title>
    <link rel="stylesheet" href="./style/signup.css">
</head>

<body>
    <div class="container">
        <div class="signup-box">
            <div class="logo">
                <img src="../images/ite.png" alt="ITE Logo">
            </div>
            <h2>Create Admin Account</h2>

            <?php if (isset($_SESSION['error']) && $_SESSION['error'] !== ""): ?>
                <p class="error-message"><?= $_SESSION['error'];
                unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="studentId">Admin ID</label>
                    <input type="text" name="studentId" id="studentId" required placeholder="Enter your Student ID"
                        required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" placeholder="Enter your first name" required>
                    </div>
                    <div class="form-group">
                        <label for="mname">Middle Name</label>
                        <input type="text" name="mname" id="mane" placeholder="Enter your middle name" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" placeholder="Enter your last name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter your phone number" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            placeholder="Confirm your password" required>
                    </div>
                </div>

                <button type="submit">Sign Up</button>
                <p class="login-link">Already have an account? <a href="index.php">Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>



<style>
    /* General Styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    body {
        background: linear-gradient(to bottom, #ff784f, #ff3d00);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Main Container */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        max-width: 450px;
        padding: 20px;
    }

    .signup-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
    }

    /* Logo */
    .logo img {
        width: 80px;
        height: 80px;
        margin-bottom: 15px;
    }

    /* Heading */
    h2 {
        color: #333;
        font-size: 22px;
        margin-bottom: 15px;
    }

    /* Form Layout */
    form {
        width: 100%;
    }

    /* Form Row (Two-Column Layout) */
    .form-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
    }

    .form-group input:focus {
        border-color: #ff3d00;
        outline: none;
        box-shadow: 0 0 8px rgba(255, 61, 0, 0.3);
    }

    /* Sign Up Button */
    button {
        background: #ff3d00;
        color: white;
        font-size: 16px;
        padding: 12px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        width: 100%;
        margin-top: 15px;
        transition: all 0.3s ease-in-out;
    }

    button:hover {
        background: #e63a00;
    }

    /* Login Link */
    .login-link {
        margin-top: 15px;
        font-size: 14px;
    }

    .login-link a {
        text-decoration: none;
        color: #ff3d00;
        font-weight: bold;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    /* Responsive Design for Small Screens */
    @media (max-width: 500px) {
        .signup-box {
            width: 90%;
            padding: 20px;
        }

        .form-row {
            flex-direction: column;
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


    p {
        margin: 0.5rem;
    }
</style>