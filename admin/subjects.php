<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

if (isset($_POST['add_subject'])) {
    $subjectCode = htmlspecialchars(trim($_POST['subject-code']));
    $subjectName = htmlspecialchars(trim($_POST['subject-name']));
    $program = intval($_POST['Program']);
    $syear = intval($_POST['syear']);
    $teacher_id = intval($_POST['teacher_id']);
    $semester = intval($_POST['semester']);
    $section = intval($_POST['section']);

    if (empty($subjectCode) || empty($subjectName) || empty($program) || empty($syear) || empty($teacher_id) || empty($semester)) {
        header("Location: subjects.php?error=All fields are required!");
        exit();
    }

    if (CheckExistingSubject($subjectCode, $subjectName)) {
        header("Location: subjects.php?error=Subject already exists!");
        exit();
    } else {
        InsertNewSubject($subjectName, $section, $program, $syear, $admin_id, $teacher_id, $subjectCode, $semester);
        header("Location: subjects.php?success=Subject added successfully!");
    }

}


if (isset($_GET['subjectID'])) {
    deleteSubject($_GET['subjectID']);
    header('Location: subjects.php?success=Subjects deleted successfully!');
    exit();
}

?>


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
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th colspan="8">Add New Subjects</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>School Year</th>
                            <th>Select Semester</th>
                            <th>Select Program</th>
                            <th>Select Section</th>
                            <th>Assign Teacher</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>
                                <input type="text" name="subject-name" required placeholder="Enter subject Name" />
                            </td>
                            <td>
                                <input type="text" name="subject-code" required placeholder="Enter subject Code" />
                            </td>
                            <td>

                                <select name="syear" required>
                                    <option value="" selected disabled>Select School Year</option>
                                    <?php
                                    $schoolyear = GetSchoolYear();
                                    foreach ($schoolyear as $schoolYears) {
                                        echo "<option value='{$schoolYears['id']}'>{$schoolYears['school_year']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="semester" required>
                                    <option value="" selected disabled>Select Semester</option>
                                    <?php
                                    $semester = GetSemester();
                                    foreach ($semester as $semesters) {
                                        echo "<option value='{$semesters['id']}'>{$semesters['semester']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="Program" required>
                                    <option value="" selected disabled>Select Program/Course</option>
                                    <?php
                                    $get_program = GetPrograms();
                                    foreach ($get_program as $getPrograms) {
                                        echo "<option value='{$getPrograms['id']}'>{$getPrograms['program_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="section" required>
                                    <option value="" selected disabled>Select Program/Course</option>
                                    <?php
                                    $get_program = getSectionList();
                                    foreach ($get_program as $getPrograms) {
                                        echo "<option value='{$getPrograms['id']}'>{$getPrograms['section_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="teacher_id" required>
                                    <option value="" selected disabled>Select Teacher</option>
                                    <?php
                                    $teachers = GetTeachers();
                                    foreach ($teachers as $teacher) {
                                        echo "<option value='{$teacher['id']}'>{$teacher['lname']}, {$teacher['fname']} {$teacher['mname'][0]}.</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="submit" name="add_subject" value="Add Subject">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <p><?php if (isset($_GET['success'])) {
                echo '<div style="color: green;">' . $_GET['success'] . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div style="color: red;">' . $_GET['error'] . '</div>';
            } ?></p>



            <div class="right-content">
                <h2>Subject List</h2>
                <div class="dashboard-container">
                    <form action="">
                        <table border="1" cellpadding="8" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Semester</th>
                                    <th>Subject Name</th>
                                    <th>Subject Code</th>
                                    <th>Section/Room</th>
                                    <th>Programs</th>
                                    <th>Subject Teacher</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                $subjects = getYearSubject();
                                if (!empty($subjects)) {
                                    foreach ($subjects as $subject) {
                                        echo "<tr>
                                                <td>{$subject['school_year']}</td>
                                                <td>{$subject['semester']}</td>
                                                <td>{$subject['name']}</td>
                                                <td>{$subject['code']}</td>
                                                <td>{$subject['section_name']}</td>
                                                <td>{$subject['program_name']}</td>
                                                <td>{$subject['teacher_full_name']}</td>
                                                
                                                <td> 
                                                    
                                                    <a href='view_Subject.php?subject_id={$subject['subject_id']}'><i class='fas fa-eye'></i></a>
                                                    <a onclick=\"return confirm('Are you sure you want to delete this minor subject?')\" href='subjects.php?subjectID={$subject['subject_id']}'><i class='fas fa-trash'></i></a>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                            <td colspan='5' style='text-align: center;'>No subjects available.</td>
                                        </tr>";

                                }
                                ?>

                            </tbody>
                    </form>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

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
        padding: 10px;
        width: calc(90% - 80px);
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 240px;
    }



    form label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
        font-size: 15px;
    }

    select {
        width: 7rem;
    }

    form input[type="text"] {
        width: 100%;
        padding: 3px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    form input[type="submit"] {
        background-color: #ff3d00;
        color: white;
        padding: 8px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
    }

    form input[type="submit"]:hover {
        background-color: #cc2c00;
    }

    /* Table Styling */
    table {

        width: 100%;
        max-width: 1000px;
        border-collapse: collapse;
        text-align: center;
        background: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }


    th,
    td {
        border: 1px solid #ddd;
        padding: 8px 12px;
        font-size: 12px;
    }

    th {
        background-color: #ff3d00;
        color: white;
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
        font-size: 12px;
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
        font-size: 12px;
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