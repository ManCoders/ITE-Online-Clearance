<?php
session_start();
include "../db_connect.php";
include "./teacher_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('location: ../index.php');
    exit();
}

$students = GetAllStudent($_SESSION["teacher_id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">

    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Georgia', serif;
        }

        /* Background - Classic Deep Maroon */
        body {
            color: #FFF;
            background: linear-gradient(to right, #6E1313, #8B0000);
            background-size: cover;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            border-right: 2px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar .profile-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile-icon {
            width: 80px;
            border-radius: 50%;
            background: white;
            padding: 5px;
            border: 2px solid #ffcc00;
        }

        .sidebar-item {
            padding: 15px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transition: 0.3s;
            font-size: 16px;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .sidebar-item a {
            color: #FFFFFF;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: bold;
        }

        .sidebar-item a i {
            margin-right: 10px;
        }

        /* Main Content Styling */
        .content {
            margin-left: 275px;
            padding: 40px;
            width: calc(100% - 280px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Dashboard Card */
        .dashboard-container {
            background: rgba(255, 255, 255, 0.15);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 600px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dashboard-container h3 {
            color: #ff3d00;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .dashboard-container p {
            font-size: 18px;
            margin: 5px 0;
        }

        .total-students {
            margin-top: 20px;
            font-size: 22px;
            font-weight: bold;
            background: #ff3d00;
            color: rgb(238, 236, 236);
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }

        /* Responsive Sidebar for Mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 220px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-info">
            <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
        </div>
        <div class="sidebar-item" style="background-color: #8B0000;">
            <a href="./index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </div>
        <div class="sidebar-item">
            <a href="./students.php"><i class="fas fa-file-alt"></i> Clearance</a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="dashboard-container">
            <h3>Instructor: <?= htmlspecialchars($students['lname'] ?? ''); ?>,
                <?= htmlspecialchars($students['fname'] ?? ''); ?>
                <?= (!empty($students['mname']) ? htmlspecialchars($students['mname'][0]) . '.' : ''); ?>
            </h3>
            <p>Email: <i><?= htmlspecialchars($students['email'] ?? 'N/A'); ?></i></p>
            <p>Employee ID: <?= htmlspecialchars($students['teacher_code'] ?? 'N/A'); ?></p>
            <p>Section Adviser: <?= htmlspecialchars($students['section'] ?? 'N/A'); ?></p>

            <?php
            $stmt = $pdo->prepare('SELECT COUNT(DISTINCT student_id) FROM student_subjects WHERE teacher_id = ?');
            $stmt->execute([$_SESSION['teacher_id']]);
            $total_students = $stmt->fetchColumn();
            ?>

            <h2 class="total-students">Total Students: <?= $total_students; ?></h2>
        </div>
    </div>

</body>

</html>