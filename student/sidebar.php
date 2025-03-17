<?php
include "../db_connect.php";

if (!isset($_SESSION['student_id'])) {
    header('location: ../index.php');
    exit();
}


$student_id = $_SESSION["student_id"];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
