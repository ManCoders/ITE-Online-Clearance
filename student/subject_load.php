<?php
session_start();
include "../db_connect.php";
include "./student_function.php";


if (!isset($_SESSION['student_id'])) {
    header('location: ../index.php');
    exit();
}

$student_id = $_SESSION['student_id'];

if (isset($_POST['new_subject'])) {
    $year = $_POST['schoolyear'];
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $semester = $_POST['semester'];
    $teacher_name = $_POST['teacher_name'];
    $program = $_POST['program'];
    if (!empty($year) && !empty($year) && !empty($subject_code) && !empty($subject_name) && !empty($semester) && !empty($teacher_name)) {
        InsertStudentSubject($program, $student_id, $year, $subject_code, $subject_name, $semester, $teacher_name);
    } else {
        header('location: ./subject_load.php?error=Please fill in all fields');
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];
    deleteStudentSubjectbyId22($subject_id);
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
        <div class="sidebar-item" style="background-color: maroon;">
            <a href="./subject_load.php"><i class="fas fa-chalkboard-teacher"></i> Subjects Load </a>
        </div>
        <div class="sidebar-item">
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <!-- Main Content -->
    <div class="content">
        <div class="dashboard-container">
            <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
            <!-- Add New student -->
            <div class="card">
                <h2>Load Available Subjects</h2>
                <?php if (isset($_GET['error'])): ?>
                    <p style="color: red;"><?php echo $_GET['error']; ?></p>
                <?php elseif (isset($_GET['success'])): ?>
                    <p style="color: green;"><?php echo $_GET['success']; ?></p>
                <?php endif; ?>


                <form name="my_form" action="" method="post">

                    <div style="display: none; flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="student_id"></label>
                        <select name="student_id" id="student_id"
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="<?php echo $_SESSION['student_id'] ?>"><?php echo $_SESSION['student_id'] ?>
                            </option>
                        </select>
                    </div>

                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="program">School Year</label>
                        <select name="schoolyear" id="schoolyear"
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select School Year</option>

                        </select>
                    </div>

                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="program">Program</label>
                        <select name="program" id="program"
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select Department Program</option>
                        </select>
                    </div>


                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="semester">Semester</label>
                        <select name="semester" id="semester" disabled
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select School Semester</option>
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>

                        </select>
                    </div>


                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="subject_code">Subject Code</label>
                        <select name="subject_code" id="subject_code" disabled
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select Subject Code</option>
                            <!--  -->
                        </select>
                    </div>



                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="subject_name">Subjects</label>
                        <select name="subject_name" id="subject_name" disabled
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select Subject Name</option>

                        </select>
                    </div>


                    <div style="display: none; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="teacher_name">Instructor</label>
                        <select name="teacher_name" id="teacher" disabled
                            style="width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select Instructor</option>
                        </select>
                    </div>

                    <select name="subject_id" hidden id="subject_id">
                        <option value="">Select subjec_id</option>
                    </select>



                    <input type="submit" name="new_subject" value="Load">
                </form>


            </div>


            <!-- *********************** STARTING HERE NAAY LABOT ************************* -->

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {

                    // Fetch programs when student_id exists
                    var student_id = $('#student_id').val();
                    if (student_id !== "") {
                        $.ajax({
                            url: "ajax.php",
                            type: "POST",
                            data: { action: 'getPrograms', student_id: student_id },
                            success: function (response) {
                                $('#program').html("<option value=''>Select Program Title</option>" + response);
                                $('#semester').prop("disabled", false);
                                $('#subject_code, #subject_name, #teacher').prop("disabled", true).html("<option value=''>Select</option>"); // Reset dependent dropdowns
                            }
                        });
                    }

                    var student_id = $('#student_id').val();
                    if (student_id !== "") {
                        $.ajax({
                            url: "ajax.php",
                            type: "POST",
                            data: { action: 'getschoolYear', student_id: student_id },
                            success: function (response) {
                                $('#schoolyear').html("<option value=''>Select Program Title</option>" + response);
                                $('#semester').prop("disabled", false);
                                $('#subject_code, #subject_name, #teacher').prop("disabled", true).html("<option value=''>Select</option>"); // Reset dependent dropdowns
                            }
                        });
                    }




                    // Fetch subjects when semester is selected
                    $('#semester').change(function () {
                        var semester = $(this).val();
                        if (semester !== "") {
                            $.ajax({
                                url: "ajax.php",
                                type: "POST",
                                data: { action: 'getSemester', semester: semester },
                                success: function (response) {
                                    $('#subject_code').html('<option value="">Select Subject Code</option>' + response);
                                    $('#subject_code').prop("disabled", false);
                                    $('#subject_name, #teacher').prop("disabled", true).html("<option value=''>Select</option>"); // Reset dependent dropdowns
                                }
                            });
                        } else {
                            $('#subject_code').prop("disabled", true).html('<option value="">Select Subject Code</option>');
                        }
                    });


                    $('#subject_code').change(function () {
                        var subjectCode = $(this).val();
                        if (subjectCode !== "") {
                            $.ajax({
                                url: "ajax.php",
                                type: "POST",
                                data: { action: 'getSubjectCode', subjectCode: subjectCode },
                                success: function (response) {
                                    $('#subject_name').html('<option value="">Select Subject Name</option>' + response);
                                    $('#subject_name').prop("disabled", false);
                                }
                            });
                        } else {
                            $('#subject_name').prop("disabled", true).html('<option value="">Select Subject Name</option>');
                        }
                    });

                    $('#subject_name, #subject_code').change(function () {
                        var subject_code = $('#subject_code').val();
                        var subject_name = $('#subject_name').val();


                        if (subject_name !== "" || subject_code !== "") {
                            $.ajax({
                                url: "ajax.php",
                                type: "POST",
                                data: { action: 'getTeacherSubjects', subject_name: subject_name, subject_code: subject_code }, // Include both fields
                                success: function (response) {
                                    $('#teacher').html('<option value="">Select Assigned Teacher</option>' + response);
                                    $('#teacher').prop("disabled", false);
                                }
                            });
                        } else {
                            $('#teacher').prop("disabled", true).html('<option value="">Select Assigned Teacher</option>');
                        }


                    });





                });

            </script>





            <!-- ************** THIS IS NOT LABOT **************** -->

            <div class="card">
                <h2>Searching</h2>
                <form method="GET">
                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for="year">School
                            Year</label>
                        <select name="year" id="yearFiter"
                            style=" width: 100%;  border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select School Year</option>
                            <?php
                            $years = GetSchoolYearOnProgram();
                            foreach ($years as $year) { ?>
                                <option value="<?php echo $year['school_year']; ?>"><?php echo $year['school_year'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div style="display: flex; margin: 5px;">
                        <label for="program" style="width: 8rem; margin: 5px;">Programs</label>
                        <select name="program" id="programFiter" onclick="getSubject(this.value)"
                            style=" width: 100%;  border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select Program</option>
                            <?php
                            $years = GetSchoolProgram();
                            foreach ($years as $year) { ?>
                                <option value="<?php echo $year['department_program']; ?>">
                                    <?php echo $year['department_program'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div style="display: flex; margin: 5px;">
                        <label style="width: 8rem; margin: 5px;" for=" program_id">Course
                        </label>
                        <select style=" width: 100%;  border: 1px solid #ccc; border-radius: 5px; " id="courseFiter"
                            name="course">
                            <option value="">Select Major Course</option>
                            <?php
                            $years = GetSchoolCourse();
                            foreach ($years as $year) { ?>
                                <option value="<?php echo $year['program_course']; ?>"><?php echo $year['program_course'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div style="display: flex; margin: 5px;">
                        <label for="semester" style="width: 8rem; margin: 5px;">Semester </label>
                        <select name="semesterFiter" style=" width: 100%;  border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Select School Semester</option>
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                        </select>
                    </div>
                    <button class="buttons" type="submit" onclick="display()" class="btn btn-primary"
                        style="margin-top: 10px; ">Filtering</button>
                </form>
                <script>

                    /* ************************ start here the fetchin Filtering sample blah blah blah ************************** */
                    $(document).ready(function () {
                        // Load programs when a school year is selected
                        $("#year").change(function () {
                            let schoolYearId = $(this).val();
                            $("#program").html('<option value="">Loading...</option>');

                            if (schoolYearId !== "") {
                                $.post("./fetch_programs.php", { school_year_id: schoolYearId }, function (data) {
                                    $("#program").html('<option value="">Select Program</option>' + data);
                                    $("#semester").removeClass("hidden");
                                    $("#course, #subject").removeClass("hidden");
                                });
                            } else {
                                $("#program, #course, #subject").addClass("hidden");
                            }
                        });

                        // Load courses when a program is selected
                        $("#program").change(function () {
                            let programId = $(this).val();
                            $("#course").html('<option value="">Loading...</option>');

                            if (programId !== "") {
                                $.post("fetch_courses.php", { program_id: programId }, function (data) {
                                    $("#course").html('<option value="">Select Course</option>' + data);
                                    $("#course-container").removeClass("hidden");
                                    $("#subject-container").addClass("hidden");
                                });
                            } else {
                                $("#course-container, #subject-container").addClass("hidden");
                            }
                        });

                        // Load subjects when a course is selected
                        $("#course").change(function () {
                            let courseId = $(this).val();
                            $("#subject").html('<option value="">Loading...</option>');

                            if (courseId !== "") {
                                $.post("fetch_subjects.php", { course_id: courseId }, function (data) {
                                    $("#subject").html('<option value="">Select Subject</option>' + data);
                                    $("#subject-container").removeClass("hidden");
                                });
                            } else {
                                $("#subject-container").addClass("hidden");
                            }
                        });
                    });



                    /* ******************************* Stop Here Fetching *********************** */




                    document.getElementById('student_name').addEventListener('input', handleInput);
                    document.getElementById('semester_id').addEventListener('input', handleInput);

                    function handleInput(event) {
                        const { id, value } = event.target;
                        console.log(`The current value of ${id} is: ${value}`);
                    }

                    function display() {
                        document.getElementById("tableNone").style.display = "block";
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
                style="width: 100%; color: #ccc; border: 1px solid #ccc; height: 10rem; background-color: #8B0000; overflow-y: scroll;">
                <div class="tables-content" style=" background-color: #8B0000; display: flex; color: white;">
                    <table class="table" style=" width: 100%; text-align: left; ">
                        <thead>
                            <tr>
                                <th>No :</th>
                                <th>Grade</th>
                                <th>Course No</th>
                                <th>Course Title</th>
                                <th>Semester</th>
                                <th>Instructor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $subject = getStudentSubject($student_id);

                            foreach ($subject as $key => $value) { ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $key + 1 ?></td>
                                    <td style="text-align: center;"><?php echo $value['grade'] ?></td>
                                    <td><?php echo $value['subject_code'] ?></td>
                                    <td><?php echo $value['subject_name'] ?>
                                    </td>
                                    <td><?php if ($value['semester'] == 1) {
                                        echo '1st Semester';
                                    } else {
                                        echo '2nd Semester';
                                    } ?>
                                    </td>

                                    <?php $teacher = getTeacherById($value['teacher_id']); ?>
                                    <td><?php echo $teacher['teacher_name']; ?></td>

                                    <td style="margin: 5px; text-align: center; color: #FFFFFF; ">
                                        <a href="subject_load.php?action=delete&subject_id=<?php echo $value['id'] ?>&student_id=<?php echo $value['student_id'] ?>"
                                            class="fa fa-trash" style="cursor: pointer; color: #FFFFFF;"></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src=" ../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
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