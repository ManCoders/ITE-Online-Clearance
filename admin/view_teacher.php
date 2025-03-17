<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}



?>

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
        margin-left: 280px;
        padding: 20px;
        width: calc(100% - 200px);
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 250px;
    }



    form label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
        font-size: 14px;
    }

    form input[type="text"] {
        width: 90%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    form input[type="submit"] {
        background-color: #ff3d00;
        color: white;
        padding: 12px 18px;
        border: none;
        border-radius: 5px;
        margin-top: 15px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: 0.3s;
    }

    form input[type="submit"]:hover {
        background-color: #cc2c00;
    }

    input[type="button"] {
        background-color: #ff3d00;
        color: white;
        padding: 12px 18px;
        border: none;
        border-radius: 5px;
        margin-top: 15px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: 0.3s;
    }

    input[type="button"]:hover {
        background-color: #cc2c00;
    }

    /* Table Styling */
    table {
        width: 100%;
        max-width: 1000px;
        border-collapse: collapse;
        margin-top: 30px;
        text-align: center;
        background: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 12px;
        font-size: 14px;
    }

    th {
        background-color: #ff3d00;
        color: white;
        font-size: 16px;
        font-weight: bold;
    }

    td a {
        text-decoration: none;
        color: #ff3d00;
        font-weight: bold;
        transition: 0.3s;
    }

    td a:hover {
        color: #cc2c00;
    }

    /* Success and Error Messages */
    p[align="center"] {
        font-weight: bold;
        margin-top: 10px;
        font-size: 14px;
    }

    h2 {
        margin-top: 20px;
        text-align: center;
        color: white;
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* Buttons inside the Table */
    td i {
        font-size: 16px;
        transition: 0.3s;
    }

    td i:hover {
        color: #cc2c00;
    }

    /* Enhancements */
    .sidebar-item a {
        font-size: 16px;
    }

    .sidebar-item a:hover {
        text-decoration: underline;
    }

    .sidebar-item[style="background-color: red;"] {
        background-color: #8B0000 !important;
        font-weight: bold;
    }

    .sidebar-item[style="background-color: red;"] a {
        color: #FFF;
    }
</style>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    </link>
</head>
<div>

    <div class="sidebar">
        <div class="profile-info">
            <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
        </div>
        <div class="sidebar-item">
            <a href="./index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
        </div>
        <div class="sidebar-item">
            <a href="./Program.php"><i class="fas fa-calendar-alt"></i> Programs</a>
        </div>
        <div class="sidebar-item">
            <a href="./subjects.php"><i class="fas fa-book"></i> Subjects</a>
        </div>
        <div class="sidebar-item">
            <a href="./students.php"><i class="fas fa-chalkboard-teacher"></i> Students</a>
        </div>
        <div class="sidebar-item" style="background-color: red;">
            <a href="./teachers.php"><i class="fas fa-chalkboard-teacher"></i>Teachers</a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="dashboard-container">





            <div class="right-content">
                <p><?php if (isset($_GET['success'])) {
                    echo '<div style="color: green;">' . $_GET['success'] . '</div>';
                } elseif (isset($_GET['error'])) {
                    echo '<div style="color: red;">' . $_GET['error'] . '</div>';
                } ?></p>
                <h2>Teacher Summary View (Maintenance)</h2>
                <div class="dashboard-container">
                    <div class="right-content">
                        <table border="1" cellpadding="4" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Complete Name</th>
                                    <th>Programs </th>
                                    <th>Subjects</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php

                                $lname = isset($_GET['lname']) ? $_GET['lname'] : '';
                                $fname = isset($_GET['fname']) ? $_GET['fname'] : '';
                                $mname = isset($_GET['mname']) && !empty($_GET['mname']) ? $_GET['mname'][0] . '.' : '';



                                $subjects = getTeacherById($_GET['studentId'] ?? '');

                                if (!empty($subjects)) {
                                    foreach ($subjects as $subject) {
                                        ?>
                                        <tr>
                                            <td><?php echo $lname . ', ' . $fname . ' ' . $mname; ?>
                                            </td>
                                            <td><?php echo $subject['teacher_code'] ?></td>
                                            <td><?php echo $subject['lname'] . ', ' . $subject['fname'] . ' ' . $subject['mname'][0] . '.'; ?>
                                            </td>
                                            <td><?php echo $subject['contact']; ?></td>
                                            <td><?php echo $subject['clearance']; ?></td>
                                            <td>
                                                <input type="button" id="student-back" name="student-back" value="Back"
                                                    onclick="history.back()">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr>
                                    <td colspan="4">Wait for the data to be loaded...</td>
                                    <td ><input type="button" id="student-back" name="student-back" value="Back"
                                                    onclick="history.back()"></td>
                                    
                                    </tr>';
                                }
                                ?>
                            </tbody>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>