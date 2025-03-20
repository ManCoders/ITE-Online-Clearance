<?php
session_start();
include "db_connect.php"; // Ensure this contains the correct PDO connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $program = $_POST["program"];
    $email = trim($_POST["email"]);
    $studentId = trim($_POST['studentId']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);



    if (empty($studentId) || empty($password) || empty($confirm_password)) {
        header('location: signup.php?error=All fields are required!');
        $_SESSION['error'] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        header('location: signup.php?error=Passwords do not match!');
        $_SESSION['error'] = "Passwords do not match!";
    } else {
        try {

            $stmt = $pdo->prepare("SELECT * FROM students WHERE student_code = ? AND email = ?");
            $stmt->execute([$studentId, $email]);
            $student_code = $stmt->fetch();


            $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_code = ?");
            $stmt->execute([$studentId]);
            $teacher_code = $stmt->fetch();


            if ($student_code) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                /*   $stmt = $pdo->prepare("UPDATE student_subjects SET program_id = ?, student_id = ? WHERE student_code = ?");
                  $stmt->execute([$program, $student_code["id"], $student_code["student_code"]]); */

                $stmt = $pdo->prepare("INSERT INTO student_login (email, password, student_id) VALUES (?, ?, ?)");
                $stmt->execute([$email, $hashed_password, $student_code['id']]);
                header('location: signup.php?success=Student Registered Successfully!');
                exit();
            } else if ($teacher_code) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                /* $stmt = $pdo->prepare("UPDATE teachers SET WHERE teacher_code = ?");
                $stmt->execute([$teacher_code["teacher_code"]]); */

                $stmt = $pdo->prepare("INSERT INTO teacher_login (email, password, teacher_id) VALUES (?, ?, ?)");
                $stmt->execute([$email, $hashed_password, $teacher_code['id']]);

                header('location: signup.php?success=Teacher Registered Successfully!');
                exit();
            } else {
                header('location: signup.php?error=Student Registered Unsuccessfully!');
            }
        } catch (PDOException $e) {
            header('location: signup.php?error=' . $e->getMessage() . '');
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITE Signup</title>
</head>

<body>
    <div class="container">
        <div class="signup-box">
            <div class="logo">
                <img src="./images/ite.png" alt="ITE Logo">
            </div>
            <h2>Create Account</h2>

            <p align="center"><?php if (isset($_GET['success'])) {
                echo '<div style="color: green;">' . $_GET['success'] . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div style="color: red;">' . $_GET['error'] . '</div>';
            } ?></p>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="studentId">Student ID</label>
                    <input type="text" name="studentId" value="<?= htmlspecialchars($_POST['studentId'] ?? '') ?>"
                        placeholder="Enter your Student ID" required>
                </div>

                <!--  <div class="form-group">
                    <label for="program">Programs courses</label>
                    <select name="program" id="program" required>
                        <option value="">Select a program</option>
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM programs");
                        $stmt->execute();
                        $programs = $stmt->fetchAll();
                        foreach ($programs as $program) {
                            echo '<option value="' . $program['id'] . '">' . $program['program_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div> -->

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <button type="submit">Sign Up</button>
                <p class="login-link">Already have an account? <a href="index.php">Login</a></p>
        </div>
        </form>
    </div>
    </div>
</body>

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

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    /* Signup Box */
    .signup-box {
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
        margin: -50px auto 10px;
        /* Moves logo slightly outside the box */
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
        background: #ff3d00;
    }

    /* Signup Link */
    .login-link {
        margin-top: 10px;
        font-size: 14px;
    }

    .login-link a {
        color: #ffcccb;
        font-weight: bold;
        text-decoration: none;
    }

    .login-link a:hover {
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