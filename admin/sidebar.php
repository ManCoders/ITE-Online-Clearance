<?php
include "../db_connect.php";


// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}


$admin_id = $_SESSION["admin_id"];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="./style.css">
<!-- 
<div class="sidebar">
    <div class="profile-info">
        <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
    </div>

    <div>
        <div class="sidebar-item">
            <a href="./index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
        </div>
        <div class="sidebar-item" style="background-color: red;">
            <a href="./Program.php"><i class="fas fa-calendar-alt"></i> Programs</a>
        </div>
        <div class="sidebar-item">
            <a href="#"><i class="fas fa-book"></i> Subjects</a>
        </div>
        <div class="sidebar-item">
            <a href="#"><i class="fas fa-chalkboard-teacher"></i> Students</a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</div> -->