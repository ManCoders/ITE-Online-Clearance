<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

$adminid = $_SESSION['admin_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-subject"])) {
    $teacher_id = $_POST['teacher_name'];
    $semester_id = $_POST['semester_id'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $program_id = $_POST['program_id'];
    InsertNewSubject2($teacher_id, $semester_id, $subject_name, $subject_code, $program_id);
}

if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];
    deleteSubjectbyId231($subject_id);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Programs & Sections</title>
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

        /* Main Content */
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 245px);
        }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }


        form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form input[type="submit"] {
            background-color: #ff3d00;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }

        form input[type="button"] {
            background-color: #ff3d00;
            width: 100%;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form input[type="submit"]:hover {
            background-color: #cc2c00;
        }

        form input[type="button"]:hover {
            background-color: #cc2c00;
        }


        .modal {
            width: 100rem;
            height: auto;
        }

        /* Modal Content */
        .modal-content {
            background-color: white;
            margin: 1% auto;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Close Button */
        .close {
            color: #6E1313;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            float: right;
        }

        .modal table {
            width: 100%;
            padding: .5rem;
            border: 1px solidrgb(122, 122, 122)
        }

        .modal th {
            background-color: #cc2c00;
            color: #FFFFFF;
        }

        .table_content {
            height: 10rem;
            background-color: #6E1313;
            overflow: hidden;
            overflow-y: scroll;
            color: antiquewhite;
        }

        .add_subject {
            display: flex;
            justify-content: center;
            flex-direction: row;
        }

        .add_subject input[type='text'] {
            width: 90%;
            height: 1%;
            margin: 5px;
        }

        .add_subject input[type='submit'] {
            width: 20%;
            align-content: center;
            margin: 5px;
        }

        .semester {
            font-size: 1rem;
            font-weight: bold;
            margin: 5px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
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
        <div class="sidebar-item" style="background-color: maroon;">
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
            <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
            <div class="modal">
                <div class="modal-content">

                    <span class="close" onclick="window.location.href = './students.php'">&times;</span>
                    <!--  -->
                    <h2 id="programTitle"><?php if (isset($_GET['student_name'])) {
                        echo $_GET['student_name'];
                    } ?></h2>
                    <span id="sy">Student ID: <?php if (isset($_GET['student_id'])) {
                        echo $_GET['student_id'];
                    } ?></span><br><span id="course">Course:
                        <?php if (isset($_GET['course_name']) && isset($_GET['program_name'])) {
                            echo $_GET['program_name'] . ' - ' . $_GET['course_name'];
                        } ?></span>


                    <p class="semester">1st Semester</p>

                    <div class="table_content" style="text-align:center;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Final</th>
                                <th>Instructor</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                            <tbody id="subjectList1">
                                <?php $subject = getSubject1($_GET['program_id']) ?>
                                <?php foreach ($subject as $index => $row) { ?>
                                    <?php if ($row['semester'] == '1') { ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $row['subject_code']; ?></td>
                                            <td><?php echo $row['subject_name']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['remark']; ?></td>
                                            <td><?php echo $row['final']; ?></td>
                                            <?php $teacher = getTeacherById($row['teacher_id']); ?>
                                            <td><?php echo $teacher['teacher_name']; ?></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>

                    </div>

                    <p class="semester">2nd Semester
                    </p>
                    <div class="table_content" style="text-align:center;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Final</th>
                                <th>Instructor</th>
                                <!-- 
                                <th>Actions</th> -->
                            </tr>

                            <tbody id="subjectList2">
                                <tr class="programRow">

                                    <?php
                                    $program_id = isset($_GET['program_id']) ? $_GET['program_id'] : '';
                                    $subject = getSubject1($program_id) ?>
                                    <?php foreach ($subject as $index => $row) { ?>
                                        <?php if ($row['semester'] == '2') { ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $row['subject_code']; ?></td>
                                            <td><?php echo $row['subject_name']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['remark']; ?></td>
                                            <td><?php echo $row['final']; ?></td>
                                            <?php $teacher = getTeacherById($row['teacher_id']); ?>
                                            <td><?php echo $teacher['teacher_name']; ?></td>
                                        </tr>
                                    <?php }
                                    } ?>
                            </tbody>
                        </table>

                    </div>
                    <!-- <p class="semester">New Subjects</p> -->
                    <?php if (isset($_GET['error'])) { ?>
                        <p style="color: red;"><?php echo $_GET['error']; ?></p>
                    <?php } elseif (isset($_GET['success'])) { ?>
                        <p style="color: green;"><?php echo $_GET['success']; ?></p>
                    <?php } ?>

                    <input
                        style="height: 2.4rem; width:20%; margin-top:5px; border: none; border-radius: 5px; color: #FFFFFF; background-color: #ff3d00;"
                        type="button" onclick="window.location.href = './students.php'" value="Back"
                        class="btn btn-secondary">
                </div>
            </div>
        </div>
    </div>

    <?php


    ?>
    <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script>



        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete_subject").forEach(button => {
                button.addEventListener("click", function (event) {
                    event.preventDefault();
                    let subject_id = this.getAttribute("subject");
                    let program_id = this.getAttribute("program");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This subject will be permanently deleted!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {

                            window.location.href = "?subject_id=" + subject_id + "&program_id=" + program_id;
                        }
                    });
                });
            });


            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has("subject_id")) {
                Swal.fire({
                    icon: "success",
                    title: "Deleted!",
                    text: "The program was successfully deleted.",
                    showConfirmButton: false,
                    timer: 2500
                });
                const newURL = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newURL);

            }

            if (urlParams.has("success")) {
                Swal.fire({
                    icon: "success",
                    title: "Successfully added!",
                    text: "The program was successfully Added!.",
                    showConfirmButton: false,
                    timer: 2500
                });
                const newURL = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newURL);

            }
        });

    </script>












</body>

</html>