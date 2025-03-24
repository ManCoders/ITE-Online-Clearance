<?php
session_start();
include "../db_connect.php";
include "./teacher_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('location: ../index.php');
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';
$students_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

if (isset($_POST['submit'])) {

    $subject_id = $_POST['subject_id'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];
    $final_remark = $_POST['final'];

    updateStudent($subject_id, $remark, $status, $final_remark);
}

?>

<!DOCTYPE html>
<html lang="en">

<body>

    <div class="sidebar">
        <div class="profile-info">
            <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
        </div>
        <div class="sidebar-item">
            <a href="./index.php"> <i class="fas fa-chart-bar"></i>Dashboard</a>
        </div>
        <div class="sidebar-item" style="background-color: maroon;">
            <a href="./students.php"><i class="fas fa-chalkboard-teacher"></i> Subjects </a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="dashboard-container">
            <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

            <?php $student = getMyId2($teacher_id) ?>

            <div class="modal">
                <?php if (isset($error_message)) { ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php } ?>

                <div class="modal-content">

                    <div style="border-radius: 10px;">
                        <?php $get_student = getStudentSubjectByid($teacher_id, $subject_id, $students_id) ?>
                        <?php foreach ($get_student as $index => $students) { ?>
                            <form id="update_remark" style="text-align: center;  display: flex; justify-content:center;"
                                action="" method="post">



                                <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">

                                <div style=" color: #FFFFFF; padding: 2rem; background-color: #8B0000; width:50%;">

                                    <?php if (isset($_GET['error'])) { ?>
                                        <p style="color: red;"><?php echo $_GET['error']; ?>
                                        </p>
                                    <?php } elseif (isset($_GET['success'])) { ?>
                                        <p style="color: green;">
                                            <?php echo $_GET['success']; ?>
                                        </p>
                                    <?php } ?>
                                    <div>
                                        <label style="margin:5px" for="subject_name">Student Name</label>
                                        <?php $student2 =  getStudentById($students['student_id']) ?>
                                        <input style=" text-align: center; " type="text" readonly
                                            value="<?php echo $student2['student_name'] ?> " name="student_name"
                                            id="subject_name">
                                        <label style="margin:5px" for="subject_name">Subject </label>
                                        <input style=" text-align: center; " type="text" readonly
                                            value=" <?php echo $students['subject_name']; ?>">
                                    </div>
                                    <div style=" margin: 5px; display: flex; gap:6.5rem;">
                                        <label for="status">Status</label>
                                        <label for="Remark">Remark</label>
                                        <label for="Final">Final</label>

                                    </div>
                                    <div style=" margin:5px; gap: 10px;">
                                        <select name="status" id="" required>
                                            <option value="">SELECT STATUS</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Drop">Drop</option>
                                            <option value="INC">INC</option>
                                        </select>
                                        <select name="remark" id="" required>
                                            <option value="">SELECT REMARK</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Lack of Requirements">Lack of Requirements</option>
                                            <option value="Not Attending">Not Attending</option>
                                            <option value="Drop out">Drop out</option>
                                        </select>
                                        <select name="final" id="" required>
                                            <option value="">SELECT FINAL</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>



                                    <div>
                                        <input type="submit" name="submit" value="Save" style="width: 30%; color: #FFFFFF;">
                                        <input type="button" style="width: 30%; color: #FFFFFF;" value="Back"
                                            onclick="history.back()">
                                    </div>
                                </div>
                            </form>
                    </div>


                    <div class="table_content" style="text-align:center; margin-top: 1rem;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Final Remark</th>
                            </tr>
                            <tbody>

                                <tr>
                                    <td>
                                        <?php echo $index + 1; ?>
                                    </td>
                                    <td>
                                        <?php echo $students['subject_name']; ?>
                                    </td>
                                    <?php $student =  getStudentById($students['student_id']) ?>
                                    <td><?php echo ($student['student_name']); ?></td>
                                    <td>
                                        <?php echo $students['student_id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $students['status']; ?>
                                    </td>
                                    <td>
                                        <?php echo $students['remark']; ?>
                                    </td>
                                    <td>
                                        <?php echo $students['final']; ?>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
            </div>

        </div>

</body>

</html>

<head>
    <meta charset=" UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel - Dashboard</title>
    <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <style>
        form select {
            padding: 5px;
            width: 32%;

        }

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
            padding: 10px;
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
            height: 35rem;
            margin: 1% auto;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
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
            border: 1px solid rgb(122, 122, 122)
        }

        .modal th {
            background-color: #cc2c00;
            color: #FFFFFF;
        }

        .table_content {
            height: 12rem;
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