<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

$teacher_id = $_GET['teacher_id'];

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel - Dashboard</title>
    <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
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
            height: auto;
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
        <!-- 
        <div class="sidebar-item">
            <a href="./subjects.php"><i class="fas fa-book"></i> Subjects</a>
        </div> -->
        <div class="sidebar-item">
            <a href="./students.php"><i class="fas fa-chalkboard-teacher"></i> Students</a>
        </div>
        <div class="sidebar-item" style="background-color: maroon;">
            <a href="./teachers.php"><i class="fas fa-chalkboard-teacher"></i>Teachers</a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>


    <div class="content">

        <div class="dashboard-container">
            <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
            <?php $student = getMyId2($teacher_id) ?>
            <?php if ($student) { ?>
                <div class="modal">
                    <div class="modal-content">
                        <h2 id="programTitle"><?php echo htmlspecialchars($student['teacher_name']) ?></h2>
                        <span id="sy">Employee ID: <?php echo $student['teacher_code']; ?></span><br>
                        <span id="course">Specialization: <?php echo $student['specialized']; ?><br />
                        </span>
                        <span id="course">Profession : <?php echo $student['profession']; ?></span><br>
                        <span id="course"><i><?php echo $student['contact']; ?> -
                                <?php echo $student['email']; ?></i></span>
                    <?php } ?>


                    <div style="margin: 10px;">
                        <h3>Total Students: <?php echo getTotalStudentByTeacherIdansStudentId($teacher_id) ?> </h3>
                        <!-- <h3>Total : <?php echo totalSubjects($teacher_id) ?> -->

                        </h3>
                    </div>
                    <div class="table_col">
                        <?php $subject = subjectList($teacher_id) ?>
                        <?php foreach ($subject as $sub) {
                            ?>
                            <?php $levels = getCollegeLevelbyTeacher($sub['college_level']) ?>
                            <label class="semester"
                                style="display:flex; justify-content:left;"><?php echo $levels['year_level']; ?> : <i>
                                    <?php echo $sub['subject_name']; ?></i></label>
                            <div class="table_content" style="text-align:center;">
                                <div
                                    style="width: 100%; text-align: center; background-color:rgb(253, 58, 4); color: #FFFFFF">
                                    <label style="padding:5px;" for="">1st
                                        Semester <input type="text" id="search1" placeholder="Search 1st Semester..."
                                            style=" width: 20%;margin-left:40rem;">
                                    </label>
                                </div>
                                <table>
                                    <tr>
                                        <th>#</th>
                                        <th>College Level</th>

                                        <th>Student Name</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Initial</th>

                                    </tr>
                                    <tbody id="subjectList1">

                                        <?php $subject = getSubjectTeacher($teacher_id) ?>
                                        <?php foreach ($subject as $index => $row) { ?>
                                            <?php if ($row['semester'] == 1 && $sub['subject_name'] == $row['subject_name'] && $row['college_level'] == $sub['college_level']) { ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>

                                                    <?php $levels = getCollegeLevelbyTeacherAdmin($row['college_level']) ?>
                                                    <td><?php echo $levels['year_level']; ?></td>

                                                    <?php $students = getStudentById($row['student_id']) ?>
                                                    <td><?php echo htmlspecialchars($students['student_name']); ?></td>
                                                    <td><?php echo $row['grade']; ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                    <td><?php echo $row['remark']; ?></td>
                                                    <td><?php echo $row['final']; ?></td>
                                                    <!-- <td>
                                                        <div style="color: aliceblue; text-align:center;">
                                                            <?php $teacher = getSubjectById2($teacher_id) ?>
                                                            <a
                                                                href="update_student.php?action=list&teacher_id=<?php echo $teacher_id; ?>&student_id=<?php echo $row['student_id']; ?>&subject_id=<?php echo $row['id']; ?>">
                                                                <i style="color: aliceblue;" class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td> -->
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>

                                </table>

                            </div>

                            <div class="table_content" style="text-align:center;">
                                <div
                                    style="width: 100%; text-align: center; background-color:rgb(253, 58, 4); color: #FFFFFF">
                                    <label style="padding:5px;" for="">2nd
                                        Semester <input type="text" id="search2" placeholder="Search 2nd Semester..."
                                            style=" width: 20%;margin-left:40rem;"></label>
                                </div>
                                <table>
                                    <tr>
                                        <th>#</th>
                                        <th>College Level</th>

                                        <th>Student Name</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Initial</th>
                                        <th>Action</th>
                                    </tr>
                                    <tbody id="subjectList2">

                                        <?php $subject = getSubjectTeacher($teacher_id) ?>
                                        <?php foreach ($subject as $index => $row) { ?>
                                            <?php if ($row['semester'] == 2 && $sub['subject_name'] == $row['subject_name'] && $row['college_level'] == $sub['college_level']) { ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>

                                                    <?php $levels = getCollegeLevelbyTeacherAdmin($row['college_level']) ?>
                                                    <td><?php echo $levels['year_level']; ?></td>

                                                    <?php $students = getStudentById($row['student_id']) ?>
                                                    <td><?php echo htmlspecialchars($students['student_name']); ?></td>
                                                    <td><?php echo $row['grade']; ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                    <td><?php echo $row['remark']; ?></td>
                                                    <td><?php echo $row['final']; ?></td>
                                                    <!-- <td>
                                                        <div style="color: aliceblue; text-align:center;">
                                                            <?php $teacher = getSubjectById2($teacher_id) ?>
                                                            <a
                                                                href="update_student.php?action=list&teacher_id=<?php echo $teacher_id; ?>&student_id=<?php echo $row['student_id']; ?>&subject_id=<?php echo $row['id']; ?>">
                                                                <i style="color: aliceblue;" class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td> -->
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>

                                </table>

                            </div>
                        <?php } ?>


                        <input
                            style="height: 2.4rem; width:20%; margin-top:5px; border: none; border-radius: 5px; color: #FFFFFF; background-color: #ff3d00;"
                            type="button" onclick="window.location.href = './teachers.php'" value="Back"
                            class="btn btn-secondary">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function filterSemester(inputId, tableBodyId) {
                $(`#${inputId}`).on('keyup', function () {
                    let query = $(this).val().toLowerCase();
                    let hasMatch = false;

                    $(`#${tableBodyId} tr`).each(function () {
                        let row = $(this);
                        let rowText = row.text().toLowerCase();

                        if (rowText.indexOf(query) > -1) {
                            row.show();
                            hasMatch = true;
                        } else {
                            row.hide();
                        }
                    });

                    if (!hasMatch) {
                        $(`#${tableBodyId}`).html(`<tr><td colspan="8" style="text-align:center; color: red;">No records found</td></tr>`);
                    }
                });
            }

            // Apply to both semesters
            filterSemester('search1', 'subjectList1');
            filterSemester('search2', 'subjectList2');
        });
    </script>