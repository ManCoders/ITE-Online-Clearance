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

            <div class="modal">s
                <div class="modal-content">


                    <p class="semester">1st Semester</p>

                    <div class="table_content" style="text-align:center;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Course</th>

                                <th>Email</th>
                                <th>Contact</th>
                                <th>Passed</th>
                                <th>Total Student</th>
                                <th>Action</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <?php $subject = getStudentSubjectByid($teacher_id, ) ?>

                                    <?php foreach ($subject as $index => $row) { ?>
                                        <?php if ($row['semester'] == 1) { ?>
                                        <tr>
                                            <?php $student_info = getStudentById($row['student_id']) ?>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $student_info['student_name']; ?></td>
                                            <?php ?>
                                            <td><?php echo $student_info['course']; ?></td>
                                            <td><?php echo $student_info['email']; ?></td>
                                            <td><?php echo $student_info['contact']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['remark']; ?></td>
                                            <td><?php echo $row['final']; ?></td>
                                            <td>
                                                <div style="color: aliceblue; text-align:center;">
                                                    <?php $teacher = getSubjectById2($teacher_id) ?>
                                                    <a href="update_student.php?teacher_id=<?php echo $teacher_id; ?>">
                                                        <i style="color: aliceblue;" class="fa fa-eye"></i>
                                                    </a>

                                                    <a href="index.php?teacher_id=<?php echo $teacher_id; ?>">
                                                        <i style="color: aliceblue;" class="fa fa-trash"></i>
                                                    </a>

                                                </div>
                                            </td>

                                        </tr>
                                    <?php }
                                    } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>


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
        </div>
</body>

</html>