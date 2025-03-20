<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}
if (isset($_POST['Update_Subject'])) {
    update_subject($_POST['sy'], $_POST['subject_id'], $_POST['subject_name'], $_POST['subject_code'], $_POST['semester'], $_POST['program'], $_POST['teacher_id']);
    header('Location: subjects.php?success=Successfully updated subject');
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
        margin-left: 275px;
        padding: 20px;
        width: calc(100% - 280px);
        display: flex;
        flex-direction: column;
        align-items: center;
    }



    form label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
        font-size: 14px;
        color: white;
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


    form input[type="button"] {
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

    form input[type="button"]:hover {
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
<div class="sidebar">
    <div class="profile-info">
        <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
    </div>
    <div class="sidebar-item">
        <a href="./index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
    </div>
    <div class="sidebar-item">
        <a href="./Program.php"><i class="fas fa-calendar-alt"></i> Programs</a>
    </div><!-- 
    <div class="sidebar-item" style="background-color: red;">
        <a href="./subjects.php"><i class="fas fa-book"></i> Subjects</a>
    </div> -->
    <div class="sidebar-item">
        <a href="./students.php"><i class="fas fa-chalkboard-teacher"></i> Students</a>
    </div>
    <div class="sidebar-item">
        <a href="./teachers.php"><i class="fas fa-chalkboard-teacher"></i>Teachers</a>
    </div>
    <div class="sidebar-item">
        <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
<div class="content">
    <div class="dashboard-container">
        <form action="" method="post">
            <h2>Update Subjects (Maintenance)</h2>

            <?php if (isset($_GET['success'])) { ?>
                <div style="color: green;"><?php echo $_GET['success']; ?></div>
            <?php } elseif (isset($_GET['error'])) { ?>
                <div style="color: red;"><?php echo $_GET['error']; ?></div>
            <?php } ?>

            <input type="hidden" name="subject_id" value="<?php echo $_GET['subjectId'] ?>">

            <label for="subject_name">Subject Name:</label>
            <input type="text" name="subject_name" value="<?php echo $_GET['subjectName'] ?>" required /><br><br>

            <label for="subject_code">Subject Code:</label>
            <input type="text" name="subject_code" value="<?php echo $_GET['subjectCode'] ?>" required /><br><br>

            <label for="semester">Semester:</label>
            <select name="semester" required>
                <?php
                $smt = $pdo->prepare('SELECT * FROM semesters');
                $smt->execute();
                $semesters = $smt->fetchAll();
                foreach ($semesters as $t) { ?>
                    <option value="<?php echo $t['id'] ?>" <?php if ($_GET['semester'] == $t['id'])
                           echo 'selected'; ?>>
                        <?php echo $t['semester']; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <label for="sy">School Year:</label>
            <select name="sy" required>
                <?php
                $smt = $pdo->prepare('SELECT * FROM school_year');
                $smt->execute();
                $years = $smt->fetchAll();
                foreach ($years as $t) { ?>
                    <option value="<?php echo $t['id'] ?>" <?php if ($_GET['semester_id'] == $t['id'])
                           echo 'selected'; ?>>
                        <?php echo $t['school_year']; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <label for="program">Select Program/Course:</label>
            <select name="program" required>
                <option value="" disabled>Select Program/Course</option>
                <?php
                $get_program = GetPrograms();
                foreach ($get_program as $getPrograms) { ?>
                    <option value="<?php echo $getPrograms['id'] ?>" <?php if ($_GET['program_id'] == $getPrograms['id'])
                           echo 'selected'; ?>>
                        <?php echo $getPrograms['program_name']; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <label for="teacher_id">Assign Teacher:</label>
            <select name="teacher_id" required>
                <option value="" disabled>Select Teacher</option>
                <?php
                $teachers = GetTeachersList();
                foreach ($teachers as $teacher) { ?>
                    <option value="<?php echo $teacher['teacher_id'] ?>" <?php if ($_GET['teacher_id'] == $teacher['id'])
                           echo 'selected'; ?>>
                        <?php echo $teacher['lname'] . " " . $teacher['fname'] . " " . $teacher['mname'][0] . "."; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <input type="submit" name="Update_Subject" value="Update Subject">
            <input type="button" id="student-back" value="Back" onclick="history.back()">
        </form>
    </div>
</div>