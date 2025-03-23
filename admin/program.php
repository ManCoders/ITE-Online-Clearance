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

if (isset($_POST['program_id'])) {
    InsertNewPrograms($_POST['course'], $_POST['department'], $_POST['sy']);
}

/* if (isset($_POST['submit3'])) {
$subjectName = $_POST['section-name'];
InsertNewSection($subjectName, $_SESSION['admin_id'], $_POST['subject-teacher-ID'], $_POST['id']);
} */

if (isset($_GET['delete_program'])) {
    DeleteProgramByID($_GET['delete_program']);
}

/* if (isset($_GET['delete_section'])) {
    DeleteSectionByID($_GET['delete_section']);
}
 */


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
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);

            padding: 20px;
            border-radius: 10px;
            width: 45%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card_table {
            background: rgba(255, 255, 255, 0.9);

            padding: 20px;
            border-radius: 10px;
            width: 50%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            overflow-y: hidden;
            overflow-y: scroll;
            height: 20rem;
            text-align: center;
        }

        .card_table td {
            width: 29rem;
            background-color: rgba(0, 0, 0, 0.03);
            display: flex;
            justify-content: space-between;
        }

        .card p {
            text-align: center;

        }

        .card h2,
        .card_table h2 {
            text-align: center;
            color: #8B0000;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
            font-size: 14px;
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
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
            width: 100%;
        }

        form input[type="submit"]:hover {
            background-color: #cc2c00;
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

    <!-- Main Content -->
    <div class="content">
        <div class="dashboard-container">
            <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

            <!-- Add Program -->
            <div class="card">
                <h2>Add New Program</h2>
                <?php if (isset($_GET['error'])): ?>
                    <p style="color: red;"><?php echo $_GET['error']; ?></p>
                <?php elseif (isset($_GET['success'])): ?>
                    <p style="color: green;"><?php echo $_GET['success']; ?></p>
                <?php endif; ?>
                <form action="" method="post">
                    <input type="text" name="id" value="<? $_GET['program_id'] ?>" hidden>
                    <label style="margin: 2px;" for=" program_id">Course Title:</label>
                    <select
                        style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                        name="course" required>
                        <option value="">Select Major Course</option>
                        <?php
                        $years = GetCourse();
                        foreach ($years as $year) { ?>
                            <option value="<?php echo $year['course_name']; ?>"><?php echo $year['course_name'] ?>
                            </option>
                        <?php } ?>

                    </select>
                    <label for="department">Programs :</label>
                    <select
                        style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                        name="department" required>
                        <option value="">Select Program</option>
                        <?php
                        $years = GetPrograms();
                        foreach ($years as $year) { ?>
                            <option value="<?php echo $year['program_code']; ?>"><?php echo $year['program_code'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <label for="sy">School Year :</label>
                    <select
                        style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                        name="sy" required>
                        <option value="">Select School Year</option>

                        <?php
                        $years = GetSchoolYear();
                        foreach ($years as $year) { ?>
                            <option value="<?php echo $year['school_year']; ?>"><?php echo $year['school_year'] ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="program_id" value="Add Program">
                </form>
            </div>

            <div class="card">
                <h2>Programs</h2>

                <form method="GET">
                    <label for="sy">School Year:</label>
                    <select name="sy"
                        style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                        <option value="">Select School Year</option>
                        <?php
                        $years = GetSchoolYear();
                        foreach ($years as $year) { ?>
                            <option value="<?php echo $year['school_year']; ?>"><?php echo $year['school_year'] ?></option>
                        <?php } ?>
                    </select>

                    <label for="department">Programs:</label>
                    <select name="department"
                        style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                        <option value="">Select Program</option>
                        <?php
                        $years = GetPrograms();
                        foreach ($years as $year) { ?>
                            <option value="<?php echo $year['program_code']; ?>"><?php echo $year['program_code'] ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label style="margin: 2px;" for=" program_id">Course Title:</label>
                    <select
                        style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                        name="course">
                        <option value="">Select Major Course</option>
                        <?php
                        $years = GetCourse();
                        foreach ($years as $year) { ?>
                            <option value="<?php echo $year['course_name']; ?>"><?php echo $year['course_name'] ?>
                            </option>
                        <?php } ?>

                    </select>

                    <button class="buttons" type="submit" onclick="display()" class="btn btn-primary"
                        style="margin-top: 10px; ">Filtering</button>
                </form>
                <style>
                    .buttons {
                        height: 2.6rem;
                        background-color: #ff3d00;
                        width: 100%;
                        margin-top: 100%;
                        color: #ffff;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        font-size: 14px;
                    }

                    #tableNone {}
                </style>

                <script>
                    document.getElementById('program_id').addEventListener('input', handleInput);
                    document.getElementById('semester_id').addEventListener('input', handleInput);

                    function handleInput(event) {
                        const { id, value } = event.target;
                        console.log(`The current value of ${id} is: ${value}`);
                    }

                    function display() {
                        document.getElementById("tableNone").style.display = "block";
                    }

                    function openModal(programId, semesterId, departmentProgram, programCourse, sy) {
                        document.getElementById("myModal").style.display = "block";

                        document.getElementById("program_id").value = programId;
                        document.getElementById("semester_id").value = semesterId;

                        document.getElementById("programTitle").innerText = departmentProgram + " - " + programCourse;
                        document.getElementById("sy").innerText = "SY: " + sy;

                    }

                    function closeModal() {
                        document.getElementById("myModal").style.display = "none";
                    }

                    window.onclick = function (event) {
                        let modal = document.getElementById("myModal");
                        if (event.target === modal) {
                            modal.style.display = "none";
                        }
                    };
                </script>
            </div>

            <div class="card-table" id="tableNone"
                style="width: 100%; color: #ccc; border: 1px solid #ccc; height: 15rem; background-color: #8B0000; overflow-y: scroll;">
                <div class="tables-content" style=" background-color: #8B0000; display: flex; color: white;">
                    <table class="table" style=" width: 100%; ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>School year</th>
                                <th>Programs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $list = GetProgramList();
                            foreach ($list as $index => $program) {
                                // Apply filter conditions
                                if (
                                    (!isset($_GET['sy']) || $_GET['sy'] == '' || $_GET['sy'] == $program['school_year']) &&
                                    (!isset($_GET['department']) || $_GET['department'] == '' || $_GET['department'] == $program['department_program']) &&
                                    (!isset($_GET['course']) || $_GET['course'] == '' || $_GET['course'] == $program['program_course'])
                                ) {
                                    ?>
                                    <tr style="text-align: center;">
                                        <td><?php echo $index + 1 ?></td>
                                        <td><?php echo htmlspecialchars($program['school_year']) ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($program['department_program']) . ' - ' . htmlspecialchars($program['program_course']); ?>
                                        </td>

                                        <td>
                                            <div style="color: aliceblue;">
                                                <a
                                                    href="view_program.php?program_id=<?php echo $_SESSION['program_id'] = $program['id']; ?>&program_name=<?php echo urlencode($program['department_program']); ?>&course_name=<?php echo urlencode($program['program_course']); ?>&school_year=<?php echo urlencode($program['school_year']); ?>">
                                                    <i style="color: aliceblue;" class="fa fa-eye"></i>
                                                </a>
                                                <!-- <a class="edit-program" section="<?php echo $program['id']; ?>"><i
                                                        class="fa fa-edit"></i></a> -->
                                                <a class="delete-program" section="<?php echo $program['id']; ?>"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {

                // Deleting Program
                document.querySelectorAll(".delete-program").forEach(button => {
                    button.addEventListener("click", function (event) {
                        event.preventDefault();
                        let programId = this.getAttribute("section");

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This program will be permanently deleted!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "?delete_program=" + programId +
                                    "&deleted_program=1";
                            }
                        });
                    });
                });

                document.querySelectorAll(".delete-section").forEach(button => {
                    button.addEventListener("click", function (event) {
                        event.preventDefault();


                        let sectionId = this.getAttribute("data-sectionid");

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This section will be permanently deleted!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "?delete_section=" + sectionId +
                                    "&deleted_section=1";
                            }
                        });
                    });
                });

                // Success message after deletion
                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has("deleted_program")) {
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

                if (urlParams.has("error")) {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "The program was unsuccessfully Added!.",
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

                if (urlParams.has("deleted_section")) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "The section was successfully deleted.",
                        showConfirmButton: false,
                        timer: 2500
                    });
                    const newURL = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, newURL);

                    /* setTimeout(() => {
                        const newURL = window.location.origin + window.location.pathname;
                                        window.history.replaceState({ }, document.title, newURL);
                    }, 10); */
                }


            });
        </script>


</body>

</html>