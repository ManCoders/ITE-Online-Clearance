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
            height: 35rem;
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
        <div class="sidebar-item" style="background-color: maroon;">
            <a href="./index.php"> <i class="fas fa-chart-bar"></i>Dashboard</a>
        </div>
        <div class="sidebar-item">
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

                    <p style="margin-top: 1.5rem;" class="semester">All Subject Hundle</p>

                    <div class="table_content" style="text-align:center;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Course Code</th>
                                <th>Course Title</th>

                                <th>Action</th>
                            </tr>
                            <tbody>
                                <?php $subject = getSubjectTeacherDashboard($teacher_id) ?>
                                <?php foreach ($subject as $index => $row) { ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo $row['subject_code']; ?></td>
                                        <td><?php echo $row['subject_name']; ?></td>


                                        <td>
                                            <a href="#?teacher_id=<?php echo $teacher_id; ?>&action=print">
                                                <i style="color: aliceblue;" class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>

                    <div style="margin:8px;">
                        <h1>Total Students: <?php echo getTotalStudentByTeacherIdansStudentId($teacher_id) ?></h1>

                    </div>



                </div>

            </div>




        </div>

    </div>
</body>

</html>