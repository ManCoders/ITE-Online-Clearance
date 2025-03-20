<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

if (isset($_POST['add_teacher'])) {
    $teacher_code = filter_var($_POST['teacher-code'], FILTER_SANITIZE_STRING);
    $teacherlname = filter_var($_POST['teacher-lname'], FILTER_SANITIZE_STRING);
    $teachermname = filter_var($_POST['teacher-mname'], FILTER_SANITIZE_STRING);
    $teacherfname = filter_var($_POST['teacher-fname'], FILTER_SANITIZE_STRING);
    $teachercontact = filter_var($_POST['teacher-contact'], FILTER_SANITIZE_STRING);

    if (CheckTeacherCode($teacher_code)) {
        header('Location: teachers.php?error=Teacher ID already exists.');
        exit();
    } else {
        InsertNewTeacher($teacher_code, $teacherlname, $teachermname, $teacherfname, $teachercontact);
    }
}


// Handle deletion safely
if (isset($_GET['teacher_code'])) {
    DeleteTeacherByID($_GET['teacher_code']);
    header("Location: teachers.php?success=Teacher deleted successfully!");
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
        <div class="sidebar-item">
            <a href="./subjects.php"><i class="fas fa-book"></i> Subjects</a>
        </div> -->
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
            <p><?php if (isset($_GET['success'])) {
                echo '<div style="color: green;">' . $_GET['success'] . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div style="color: red;">' . $_GET['error'] . '</div>';
            } ?></p>
            <form action="teachers.php" method="post">

                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th colspan="6">Add New Teacher</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
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
                                <input type="text" name="teacher-code" placeholder="Enter Employee ID" required />
                            </td>
                            <td>
                                <input type="text" name="teacher-fname" id="teacher-fname"
                                    placeholder="Enter First Name" required />
                            </td>
                            <td>
                                <input type="text" name="teacher-mname" placeholder="Enter Middle Name" required />
                            </td>
                            <td>
                                <input type="text" name="teacher-lname" placeholder="Enter Last Name" required />
                            </td>
                            <td>
                                <input type="text" name="teacher-contact" placeholder="Enter Contact Number" required />
                            </td>
                            <td>
                                <input type="submit" name="add_teacher" value="Add Teacher">

                            </td>
                        </tr>

                    </tbody>
                </table>
            </form>




            <div class="right-content">
                <h2>Teacher List</h2>

                <form method="GET" action="teachers.php"
                    style="display: flex; align-items: center; justify-content: center; margin-top: 17px;">
                    <input type="text" name="search" placeholder="Search by Teacher ID or Name"
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                        style="width: 20%; padding: 10px; margin-right: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                    <input type="submit" value="Search"
                        style="background-color: #ff3d00; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold; transition: 0.3s;">
                </form>

                <table border="1" cellpadding="6" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Registration ID</th>
                            <th>Complete Name</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";
                        $teachers = TeacherNonAct();

                        $found = false; // Track if any matching teachers are found
                        
                        if (!empty($teachers)) {
                            foreach ($teachers as $teacher) {
                                // Check if search query is provided and filter results
                                if ($searchQuery !== "") {
                                    $fullName = strtolower($teacher['fname'] . " " . $teacher['lname'] . " " . $teacher['mname']);
                                    $teacherCode = strtolower($teacher['teacher_code']);
                                    $searchTerm = strtolower($searchQuery);

                                    if (strpos($fullName, $searchTerm) === false && strpos($teacherCode, $searchTerm) === false) {
                                        continue; // Skip if search term doesn't match
                                    }
                                }

                                $found = true;
                                echo "<tr>
                        <td>{$teacher['teacher_code']}</td>
                        <td>{$teacher['lname']}, {$teacher['fname']} " . (!empty($teacher['mname']) ? $teacher['mname'][0] . '.' : '') . "</td>
                        <td>{$teacher['contact']}</td>
                        <td>
                            <a href='edit_teacher.php?teacher_code={$teacher['teacher_code']}&fname={$teacher['fname']}&lname={$teacher['lname']}&mname={$teacher['mname']}&contact={$teacher['contact']}&section={$teacher['section']}&profile={$teacher['profile']}'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <a onclick=\"return confirm('Are you sure you want to delete this teacher?')\" href='teachers.php?teacher_code={$teacher['teacher_code']}'>
                                <i class='fas fa-trash-alt'></i>
                            </a>
                        </td>
                    </tr>";
                            }
                        }

                        // Show "No teachers found" message only if no matching teachers exist
                        if (!$found) {
                            echo "<tr><td colspan='4'>No teachers found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
        margin-left: 270px;
        padding: 20px;
        width: calc(100% - 320px);
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 300px;
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