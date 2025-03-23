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
    InsertNewSubjectByAdmin($teacher_id, $semester_id, $subject_name, $subject_code, $program_id);
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['program_id']) && isset($_GET['subject_id'])) {
    $program_id = $_GET['program_id'];
    $subject_id = $_GET['subject_id'];
    deleteSubjectbyId($subject_id, $program_id);
}


if (isset($_POST['Updating_form'])) {
    $teacher_id = $_POST['teacher_id'];
    $subject_id = $_POST['subject_id'];
    $program_id = $_POST['program_id'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $semester = $_POST['semester_id'];

    editSubjectById($teacher_id, $subject_id, $program_id, $subject_name, $subject_code, $semester);
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
            width: 100%;
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
            height: 9.7rem;
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
        <div class="sidebar-item" style="background-color: maroon;">
            <a href="./Program.php"><i class="fas fa-calendar-alt"></i> Programs</a>
        </div><!-- 
        <div class="sidebar-item">
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
            <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
            <div class="modal">
                <div class="modal-content">

                    <span class="close" onclick="window.location.href = './program.php'">&times;</span>


                    <?php
                    if (!isset($_GET['program_id'])) {
                    } else {
                        $course = GetProgramsById($_GET['program_id']);
                        ?>
                        <h2 id="programTitle">
                            <?php echo $course['department_program'] . ' - ' . $course['program_course']; ?>
                        </h2>
                        <span id="sy">SY: <?php echo $course['school_year']; ?></span>
                    <?php } ?>
                    <p class="semester">1st Semester
                    </p>

                    <div class="table_content" style="text-align:center;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Instructor</th>
                                <th>Actions</th>
                            </tr>
                            <tbody id="subjectList1">

                                <?php
                                if (!isset($_SESSION['program_id'])) {
                                    echo '<tr><td colspan="5">No programs found.</td></tr>';
                                }

                                $subjects = getSubjectById($_GET['program_id']);
                                $index = 1;

                                foreach ($subjects as $subject) {
                                    if ($subject['semester'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $index++; ?>
                                            </td>
                                            <td>
                                                <?php echo $subject['subject_code']; ?>
                                            </td>
                                            <td>
                                                <?php echo $subject['subject_name']; ?>
                                            </td>
                                            <?php $teacher = getTeacherById($subject['teacher_id']); ?>
                                            <td><?php echo $teacher['teacher_name']; ?></td>

                                            <?php $program = getProgramById($subject['program_id']) ?>
                                            <td>
                                                <a style="color: white;" class="fa fa-trash"
                                                    href="./view_program.php?action=delete&program_id=<?php echo $subject['program_id']; ?>&subject_id=<?php echo $subject['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this subject?')"></a>
                                                <a style="color: white;" class="fa fa-edit" id="editform"
                                                    href="./view_program.php?action=edit&program_id=<?php echo $subject['program_id']; ?>&teacher_id=<?php echo $subject['teacher_id']; ?>&subject_id=<?php echo $subject['id']; ?>&subject_code=<?php echo $subject['subject_code']; ?>&subject_name=<?php echo $subject['subject_name']; ?>&teacher_name=<?php echo $subject['teacher_name']; ?>&semester=<?php echo $subject['semester']; ?>"></a>
                                            </td>
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
                                <th>Instructor</th>
                                <th>Actions</th>
                            </tr>

                            <tbody id="subjectList2">
                                <tr class="programRow">
                                    <?php
                                    if (!isset($_SESSION['program_id'])) {
                                        echo '<tr><td colspan="5">No programs found.</td></tr>';
                                    }

                                    $subjects = getSubjectById($_GET['program_id']);
                                    $index = 1;

                                    foreach ($subjects as $subject) {
                                        if ($subject['semester'] == 2) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $index++; ?>
                                            </td>
                                            <td>
                                                <?php echo $subject['subject_code']; ?>
                                            </td>
                                            <td>
                                                <?php echo $subject['subject_name']; ?>
                                            </td>
                                            <?php $teacher = getTeacherById($subject['teacher_id']); ?>
                                            <td><?php echo $teacher['teacher_name']; ?></td>

                                            <?php $program = getProgramById($subject['program_id']) ?>
                                            <td>
                                                <a style="color: white;" class="fa fa-trash"
                                                    href="./view_program.php?action=delete&program_id=<?php echo $subject['program_id']; ?>&subject_id=<?php echo $subject['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this subject?')"></a>
                                                <a style="color: white;" class="fa fa-edit" id="editform"
                                                    href="./view_program.php?action=edit&program_id=<?php echo $subject['program_id']; ?>&teacher_id=<?php echo $subject['teacher_id']; ?>&subject_id=<?php echo $subject['id']; ?>&subject_code=<?php echo $subject['subject_code']; ?>&subject_name=<?php echo $subject['subject_name']; ?>&teacher_name=<?php echo $subject['teacher_name']; ?>&semester=<?php echo $subject['semester']; ?>"></a>
                                            </td>
                                        </tr>
                                    <?php }
                                    } ?>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                    <p class="semester" id="semester_label">New Subjects</p>
                    <?php if (isset($_GET['error'])) { ?>
                        <p style="color: red;"><?php echo $_GET['error']; ?></p>
                    <?php } elseif (isset($_GET['success'])) { ?>
                        <p style="color: green;"><?php echo $_GET['success']; ?></p>
                    <?php } ?>

                    <form id="submitting_form" action="" method="post">
                        <div class="add_subject">

                            <div style="gap: 2px; display: flex;">

                                <input type="text" name="program_id" value="<?php echo $_GET['program_id']; ?>"
                                    style="display: none;">
                                <input style="height: 2.4rem; margin-top:5px; width:20rem;" type="text"
                                    name="subject_code" placeholder="Enter Subject Code" required>

                                <input style="height: 2.4rem; margin-top:5px; width:20rem;" type="text"
                                    name="subject_name" placeholder="Enter Subject Name" required>

                                <select style="height: 2.4rem; margin-top:5px;" name="semester_id" required>
                                    <option value="">Select Semester</option>
                                    <?php $semester = GetSemester(); ?>
                                    <?php foreach ($semester as $s) { ?>
                                        <option value="<?php echo $s['id']; ?>"><?php echo $s['semester']; ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <?php $teacher = TeacherList(); ?>
                                <select style="height: 2.4rem; margin-top:5px;" name="teacher_name" id="teacher">
                                    <option value="">Select Teacher</option>
                                    <?php foreach ($teacher as $t) { ?>
                                        <option value="<?php echo $t['id']; ?>"><?php echo $t['teacher_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <input section=".<?php echo $_GET['program_id'] ?>."
                                    style="height: 2.4rem; width:100%; margin-top:5px; color: #FFFFFF;" type="submit"
                                    name="add-subject" id="add-subject" value="Add" class="btn btn-primary add-subject">

                                <input type="button" onclick="window.location.href = './program.php'" value="Back"
                                    class="btn btn-secondary" style="color: #FFFFFF;">
                            </div>

                        </div>
                    </form>

                    <form action="" id="popid" style="display: block;" method="post">
                        <div class="add_subject">

                            <div style="gap: 2px; display: flex; width: 99%;">

                                <input type="text" name="program_id"
                                    value="<?php echo isset($_GET['program_id']) ? $_GET['program_id'] : ''; ?>"
                                    style="display: none;">
                                <input type="text" hidden name="subject_id"
                                    value="<?php echo isset($_GET['subject_id']) ? $_GET['subject_id'] : ''; ?>">
                                <input style="height: 2.4rem; margin-top:5px; width:100%;" type="text"
                                    name="subject_code" placeholder="Update Subject Code"
                                    value="<?php echo (isset($_GET['subject_code'])) ? $_GET['subject_code'] : ''; ?>">

                                <input style="height: 2.4rem; margin-top:5px; width:100%;" type="text"
                                    name="subject_name"
                                    value="<?php echo (isset($_GET['subject_name'])) ? $_GET['subject_name'] : ''; ?>"
                                    placeholder="Update Subject Name" required>

                                <select style="height: 2.4rem; margin-top:5px;" name="semester_id" required>
                                    <option value="">Select Semester</option>
                                    <?php $semester = GetSemester(); ?>
                                    <?php foreach ($semester as $s) { ?>
                                        <option value="<?php echo $s['id']; ?>" <?php if (isset($_GET['semester']) && $_GET['semester'] == $s['id'])
                                               echo 'selected'; ?>><?php echo $s['semester']; ?>
                                        </option>
                                    <?php } ?>
                                </select>


                                <select style="height: 2.4rem; margin-top:5px;" name="teacher_id" id="teacher">
                                    <?php $teacher = TeacherList(); ?>
                                    <option value="">Select Teacher</option>
                                    <?php foreach ($teacher as $t) { ?>
                                        <option value="<?php echo $t['id']; ?>" <?php if (isset($_GET['teacher_id']) && $_GET['teacher_id'] == $t['id'])
                                               echo 'selected'; ?>><?php echo $t['teacher_name']; ?></option>
                                    <?php } ?>
                                </select>

                                <input style="height: 2.4rem; width:100%; margin-top:5px; color: #FFFFFF;" type="submit"
                                    name="Updating_form" id="Updating_form" value="Update" class="btn btn-primary">

                                <input id="back-update" type="button" onclick="window.location.href = './program.php'"
                                    value="Back" class="btn" style="color: #FFFFFF;">
                            </div>

                        </div>
                    </form>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        /*  $(document).ready(function () {
                             $('.fa-edit').on('click', function (e) {
                                 e.preventDefault();
 
                                 $('#submitting_form').hide();
                                 $('#popid').show();
 
 
                                 $('#semester_label').text('Updating form');
 
 
                                 $('#Updating_form').val('Update');
                             });
 
 
                         }); */

                    </script>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>


</body>

</html>