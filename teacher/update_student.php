<?php
session_start();
include "../db_connect.php";
include "./teacher_function.php";

if (!isset($_SESSION['teacher_id'])) {
    header('location: ../index.php');
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

if (!isset($_GET['student_id']) || !isset($_GET['subject_id'])) {
    header("Location: students.php?error=Invalid request!");
    exit();
}

$student_id = $_GET['student_id'];
$subject_id = $_GET['subject_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    $clearance = $_POST['clearance'] ?? null;
    $remark = $_POST['remarks'] ?? null;
    $final_grade = $_POST['student-finals-grades'] ?? null;

    if (empty($clearance) || empty($remark) || empty($final_grade)) {
        header("Location: students.php?error=All fields are required!");
        exit();
    }

    $query = "UPDATE student_subjects 
              SET remark = ?, clearance = ?, finalterm = ? 
              WHERE student_id = ? AND id = ? AND teacher_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$remark, $clearance, $final_grade, $student_id, $subject_id, $teacher_id]);

    if ($stmt->rowCount() > 0) {
        header("Location: students.php?success=Student updated successfully!");
    } else {
        header("Location: students.php?error=No changes made or invalid student/subject.");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">

</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-info">
            <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
        </div>
        <div class="sidebar-item">
            <a href="./index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </div>
        <div class="sidebar-item" style="background-color: red;">
            <a href="./students.php"><i class="fas fa-file-alt"></i> Clearance</a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="dashboard-container">
            <form action="" method="post">
                <h2>Update Student</h2>

                <label for="student-finals-grades">Final Grade:</label>
                <select id="student-finals-grades" name="student-finals-grades" required>
                    <option value="" disabled>Select Final Grade</option>
                    <option value="Passed" <?php if (isset($_GET['final_grade']) && $_GET['final_grade'] == 'Passed')
                        echo 'selected'; ?>>Passed</option>
                    <option value="Failed" <?php if (isset($_GET['final_grade']) && $_GET['final_grade'] == 'Failed')
                        echo 'selected'; ?>>Failed</option>
                </select>

                <label for="student-remarks">Remarks:</label>
                <select id="student-remarks" name="remarks" required>
                    <?php
                    if (isset($_GET['final_grade']) && $_GET['final_grade'] == "Passed") {
                        echo "<option value='No Remarks' selected>No Remarks</option>";
                    } else {
                        $selectedRemark = isset($_GET['remark']) ? $_GET['remark'] : "";
                        echo "<option value='Lack of Requirement' " . ($selectedRemark == 'Lack of Requirement' ? 'selected' : '') . ">Lack of Requirement</option>";
                        echo "<option value='Not Attending Classes' " . ($selectedRemark == 'Not Attending Classes' ? 'selected' : '') . ">Not Attending Classes</option>";
                        echo "<option value='No Exam' " . ($selectedRemark == 'No Exam' ? 'selected' : '') . ">No Exam</option>";
                    }
                    ?>
                </select>

                <label for="student-clearance">Clearance status:</label>
                <select id="student-clearance" name="clearance" required>
                    <option value="" disabled>Select Clearance</option>
                    <option value="Cleared" <?php if (isset($_GET['clearance']) && $_GET['clearance'] == 'Cleared')
                        echo 'selected'; ?>>Cleared</option>
                    <option value="Not Cleared" <?php if (isset($_GET['clearance']) && $_GET['clearance'] == 'Not Cleared')
                        echo 'selected'; ?>>Not Cleared</option>
                </select>

                <input type="submit" name="update_student" value="Update Student">
                <input type="button" id="student-back" name="student-back" value="Back" onclick="history.back()">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let gradeSelect = document.getElementById("student-finals-grades");
            let remarksSelect = document.getElementById("student-remarks");

            function toggleRemarks() {
                if (gradeSelect.value === "Passed") {
                    remarksSelect.innerHTML = "<option value='Completed' selected>Completed</option>";
                } else if (gradeSelect.value === "Failed") {
                    remarksSelect.innerHTML = `
                            <option value="Lack of Requirement" ${"<?php if ($_GET['remark'] == 'Lack of Requirement')
                                echo 'selected'; ?>"}>Lack of Requirement</option>
                            <option value="Not Attending Classes" ${"<?php if ($_GET['remark'] == 'Not Attending Classes')
                                echo 'selected'; ?>"}>Not Attending Classes</option>
                            <option value="No Exam" ${"<?php if ($_GET['remark'] == 'No Exam')
                                echo 'selected'; ?>"}>No Exam</option>
                        `;
                }
            }
            gradeSelect.addEventListener("change", toggleRemarks);
            toggleRemarks(); // Apply selection on page load
        });
    </script>

</body>

</html>
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
        display: flex;
        height: 100vh;
        overflow: hidden;
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
        text-align: center;
    }

    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    .sidebar-item a {
        color: #FFFFFF;
        text-decoration: none;
        display: block;
        font-weight: bold;
    }

    .sidebar-item a i {
        margin-right: 10px;
    }

    .sidebar-item[style="background-color: red;"] {
        background-color: #8B0000 !important;
        font-weight: bold;
    }

    .sidebar-item[style="background-color: red;"] a {
        color: #FFF;
    }

    /* Main Content Styling */
    .content {
        margin-left: 270px;
        padding: 20px;
        width: calc(100% - 270px);
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow-y: auto;
    }

    /* Form Styling */
    form {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        backdrop-filter: blur(5px);
        text-align: center;
    }

    form label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
        font-size: 14px;
        color: white;
    }

    form select,
    form input[type="submit"],
    form input[type="button"] {
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        border-radius: 5px;
        font-size: 14px;
    }

    form select {
        background: white;
        border: 1px solid #ccc;
    }

    form input[type="submit"],
    form input[type="button"] {
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }

    form input[type="submit"] {
        background-color: #ff3d00;
        color: white;
    }

    form input[type="submit"]:hover {
        background-color: #cc2c00;
    }

    form input[type="button"] {
        background-color: #ff3d00;
        color: white;
    }

    form input[type="button"]:hover {
        background-color: #cc2c00;
    }

    /* Table Styling */
    table {
        width: 100%;
        max-width: 800px;
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

    h2 {
        margin-top: 20px;
        text-align: center;
        color: white;
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
    }
</style>