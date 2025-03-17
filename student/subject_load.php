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


<?php
session_start();
include "../db_connect.php";
include "./student_function.php";

if (!isset($_SESSION['student_id'])) {
    header('location: ../index.php');
    exit();
}

$student_id = $_SESSION['student_id'];

?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
</head>

<?php include "./sidebar.php"; ?>

<div class="sidebar">
    <div class="profile-info">
        <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
    </div>
    <div class="sidebar-item">
        <a href="./index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
    </div>
    <div class="sidebar-item" style="background-color: red;">
        <a href="./subject_load.php"><i class="fas fa-chalkboard-teacher"></i> Subjects Load </a>
    </div>
    <div class="sidebar-item">
        <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="content">
    <div class="dashboard-container">
        <p>
            <?php if (isset($_GET['success'])) {
                echo '<div style="color: green;">' . $_GET['success'] . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div style="color: red;">' . $_GET['error'] . '</div>';
            } ?>
        </p>

        <div class="right-content">
            <h2>Subject List</h2>
            <div class="dashboard-container">
                <form action="./process_subjects.php" method="post">

                    <table border="1" cellpadding="8" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes()"></th>
                                <th>School Year</th>
                                <th>Semester</th>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Section/Room</th>
                                <th>Programs</th>
                                <th>Subject Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subjects = getYearSubjectStudent();
                            if (!empty($subjects)) {
                                foreach ($subjects as $subject) {
                                    echo "<tr>
                                            <td>
                                                <input type='checkbox' name='selected_subjects[]' value='{$subject['subject_id']}' class='subjectCheckbox'>
                                            </td>
                                            <td>{$subject['school_year']}</td>
                                            <td>{$subject['semester']}</td>
                                            <td>{$subject['name']}</td>
                                            <td>{$subject['code']}</td>
                                            <td>{$subject['section_name']}</td>
                                            <td>{$subject['program_name']}</td>
                                            <td>{$subject['teacher_full_name']}</td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='8' style='text-align: center;'>No subjects available.</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <input type="submit" name="submit_selected" value="Process Selected Subjects">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Select All -->
<script>
    function toggleAllCheckboxes() {
        var selectAll = document.getElementById("selectAll");
        var checkboxes = document.querySelectorAll(".subjectCheckbox");

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }
</script>







<script src="./jquery-3.6.0.min.js"></script>