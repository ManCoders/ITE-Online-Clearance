<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

if (isset($_POST['add_student'])) {
    $studentid = $_POST['student-code'];
    $lname = $_POST['student-lname'];
    $mname = $_POST['student-mname'];
    $fname = $_POST['student-fname'];
    $contact = $_POST['student-contact'];


    $existingStudent = CheckStudentCode($studentid);
    if ($existingStudent) {
        header('Location: students.php ?error=Student ID already exists.');
        exit();
    } else {
        InsertNewStudentByID($studentid, $lname, $mname, $fname, $contact);
    }
}


if (isset($_GET['studentId'])) {
    DeleteStudentByID($_GET['studentId']);
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
        <div class="sidebar-item">
            <a href="./subjects.php"><i class="fas fa-book"></i> Subjects</a>
        </div> -->

        <div class="sidebar-item" style="background-color: red;">
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
            <p><?php if (isset($_GET['success'])) {
                echo '<div style="color: green;">' . $_GET['success'] . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div style="color: red;">' . $_GET['error'] . '</div>';
            } ?></p>
            <form action="" method="post">
                <table border="1" cellpadding="5" cellspacing="0">

                    <thead>
                        <tr>
                            <th colspan="6">Add New Students</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Student Code</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Student Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>
                                <input type="text" name="student-code" placeholder="Enter Student Code" required />
                            </td>
                            <td>
                                <input type="text" name="student-fname" id="Student-fname"
                                    placeholder="Enter First Name" required />
                            </td>
                            <td>
                                <input type="text" name="student-mname" placeholder="Enter Middle Name" required />
                            </td>
                            <td>
                                <input type="text" name="student-lname" placeholder="Enter Last Name" required />
                            </td>
                            <td>
                                <input type="text" name="student-contact" placeholder="Enter Student Contact"
                                    required />
                            </td>
                            <td>
                                <input type="submit" name="add_student" value="Add Student">
                            </td>
                        </tr>

                    </tbody>
                </table>
            </form>

        </div <div class="right-content">
        <h2>Student List</h2>

        <form method="GET" action="students.php"
            style="display: flex; align-items: center; justify-content: center; margin-top: 17px;">
            <input type="text" name="search" placeholder="Search by Student ID or Name"
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                style="width: 70%; padding: 10px; margin-right: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
            <input type="submit" value="Search"
                style="background-color: #ff3d00; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold; transition: 0.3s;">
        </form>

        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>Registration Id</th>
                    <th>Complete Name</th>
                    <th>Section</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="card-table" id="tableNone"
            style="width: 100%; color: #ccc; border: 1px solid #ccc;  background-color: #8B0000; overflow-y: scroll;">
            <div class="tables-content" style=" background-color: #8B0000; display: flex; color: white;">
                <table class="table" style=" width: 100%; ">
                    <tbody>
                        <?php
                        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";
                        $students = StudentNonAct();

                        $found = false; // Track if any matching students are found
                        
                        if (!empty($students)) {
                            foreach ($students as $student) {
                                if (empty($student['verify'])) {
                                    // Check if search query is provided and filter results
                                    if ($searchQuery !== "") {
                                        $fullName = strtolower($student['fname'] . " " . $student['lname'] . " " . $student['mname']);
                                        $studentCode = strtolower($student['student_code']);
                                        $searchTerm = strtolower($searchQuery);

                                        if (strpos($fullName, $searchTerm) === false && strpos($studentCode, $searchTerm) === false) {
                                            continue; // Skip if search term doesn't match
                                        }
                                    }

                                    $found = true;
                                    echo "<tr>
                        <td>{$student['student_code']}</td>
                        <td>{$student['fname']}, {$student['lname']} {$student['mname'][0]}.</td>
                        <td>" . (!empty($student['section']) ? $student['section'] : 'No Section') . "</td>
                        <td>
                            <a href='edit_student.php?student_code={$student['student_code']}&fname={$student['fname']}&lname={$student['lname']}&mname={$student['mname']}&contact={$student['contact']}&section={$student['section']}&profile={$student['profile']}'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <a onclick=\"return confirm('Are you sure you want to delete this student?')\" href='students.php?studentId={$student['student_code']}'>
                                <i class='fas fa-trash-alt'></i>
                            </a>
                            <a href='view_Student.php?studentId={$student['id']}&student_code={$student['student_code']}&fname={$student['fname']}&lname={$student['lname']}&mname={$student['mname']}&contact={$student['contact']}&section={$student['section']}&profile={$student['profile']}'>
                                <i class='fas fa-eye'></i>
                            </a>
                        </td>
                    </tr>";
                                }
                            }
                        }

                        // Show "No students found" message only if no matching students exist
                        if (!$found) {
                            echo "<tr><td colspan='4'>No students found.</td></tr>";
                        }
                        ?><?php
                        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";
                        $students = StudentNonAct();

                        $found = false; // Track if any matching students are found
                        
                        if (!empty($students)) {
                            foreach ($students as $student) {
                                if (empty($student['verify'])) {
                                    // Check if search query is provided and filter results
                                    if ($searchQuery !== "") {
                                        $fullName = strtolower($student['fname'] . " " . $student['lname'] . " " . $student['mname']);
                                        $studentCode = strtolower($student['student_code']);
                                        $searchTerm = strtolower($searchQuery);

                                        if (strpos($fullName, $searchTerm) === false && strpos($studentCode, $searchTerm) === false) {
                                            continue; // Skip if search term doesn't match
                                        }
                                    }

                                    $found = true;
                                    echo "<tr>
                        <td>{$student['student_code']}</td>
                        <td>{$student['fname']}, {$student['lname']} {$student['mname'][0]}.</td>
                        <td>" . (!empty($student['section']) ? $student['section'] : 'No Section') . "</td>
                        <td>
                            <a href='edit_student.php?student_code={$student['student_code']}&fname={$student['fname']}&lname={$student['lname']}&mname={$student['mname']}&contact={$student['contact']}&section={$student['section']}&profile={$student['profile']}'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <a onclick=\"return confirm('Are you sure you want to delete this student?')\" href='students.php?studentId={$student['student_code']}'>
                                <i class='fas fa-trash-alt'></i>
                            </a>
                            <a href='view_Student.php?studentId={$student['id']}&student_code={$student['student_code']}&fname={$student['fname']}&lname={$student['lname']}&mname={$student['mname']}&contact={$student['contact']}&section={$student['section']}&profile={$student['profile']}'>
                                <i class='fas fa-eye'></i>
                            </a>
                        </td>
                    </tr>";
                                }
                            }
                        }

                        // Show "No students found" message only if no matching students exist
                        if (!$found) {
                            echo "<tr><td colspan='4'>No students found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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