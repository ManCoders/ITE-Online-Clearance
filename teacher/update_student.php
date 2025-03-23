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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];
    $final_remark = $_POST['final_remark'];

    // Update student status in the database
    $stmt = $pdo->prepare("UPDATE student_subjects SET status = ?, remark = ?, finalterm = ? WHERE student_id = ? AND teacher_id = ? AND subject_id = ?");
    $stmt->execute([$status, $remark, $final_remark, $student_id, $teacher_id, $subject_id]);

    header("Location: update_student.php?subject_id=$subject_id&success=Student status updated successfully.");
    exit();
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

                    <h2>Master List</h2>
                    <div class="table_content" style="text-align:center;">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Final Remark</th>
                                <th>Action</th>
                            </tr>
                            <tbody>
                                <?php 
                                $students = GetStudentByTeacherId($teacher_id, $subject_id); // Fetch all students for the teacher under the subject


                                if (empty($students)) {
                                    echo "<tr><td colspan='8'>No students found for this teacher.</td></tr>";
                                } else {
                                    foreach ($students as $index => $row) { 
                                        $student_info = getStudentById($row['student_id']);
                                        if (!$student_info) {
                                            $error_message = "Student information could not be retrieved.";
                                        }
                                ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo $row['subject_code']; ?></td>
                                        <td><?php echo $student_info['student_name']; ?></td>
                                        <td><?php echo $student_info['email']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td><?php echo $row['remark']; ?></td>
                                        <td><?php echo $row['final']; ?></td>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                                                <select name="status">
                                                    <option value="completed">Completed</option>
                                                    <option value="incomplete">Incomplete</option>
                                                    <option value="drop">Drop</option>
                                                </select>
                                                <select name="remark">
                                                    <option value="lack of requirement">Lack of Requirement</option>
                                                    <option value="no attending class">No Attending Class</option>
                                                    <option value="drop">Drop</option>
                                                </select>
                                                <select name="final_remark">
                                                    <option value="passed">Passed</option>
                                                    <option value="failed">Failed</option>
                                                </select>
                                                <input type="submit" value="Update">
                                            </form>
                                        </td>
                                    </tr>
                                <?php 
                                    } 
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <form>
                        <input type="button"
                            style="width: 20%; margin-top:5px; cursor: pointer; padding: 6px; border-radius: 5px; color: #FFFFFF; background-color: #ff3d00; border: none;"
                            value="Back" onclick="history.back()">
                    </form>

                </div>

            </div>

        </div>
</body>

</html>

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
            border: 1px solid rgb(122, 122, 122)
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
