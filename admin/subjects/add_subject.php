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
    $program_id = $_POST['program-name'];
    InsertNewPrograms($program_id,  $_POST['department-name']);
}

if (isset($_POST['submit3'])) {
    $subjectName = $_POST['section-name'];
    InsertNewSection($subjectName, $_SESSION['admin_id'], $_POST['subject-teacher-ID']);
}

if (isset($_GET['delete_program'])) {
    DeleteProgramByID($_GET['delete_program']);
}

if (isset($_GET['delete_section'])) {
    DeleteSectionByID($_GET['delete_section']);
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
            height: auto;
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

        .card h2, .card_table h2 {
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


        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Modal Content */
        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Close Button */
        .close {
            color: red;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            float: right;
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
        </div>
        <div class="sidebar-item">
            <a href="./subjects.php"><i class="fas fa-book"></i> Subjects</a>
        </div>
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
                <form action="" method="post">
                    <label>Majors:</label>
                    <input type="text" name="program-name" required placeholder="Enter ex'Information Technology">
                    <label for="department">Program course</label>
                    <input type="text" name="department-name" required placeholder="Enter ex' DT, AIT or TITE">
                    <input type="submit" name="program_id" value="Add Program">
                </form>
            </div>

            <div class="card_table">
                <h2>Programs</h2>
                <table class="table table-striped table-bordered">
                    
                    <tbody>
                        <?php
                        $list = GetProgramList();
                        foreach ($list as $index => $program) { ?>
                            <tr>
                                <td>
                                    <?php echo ($index + 1) . '. '. htmlspecialchars($program['program_code']). ' - ' . htmlspecialchars($program['program_name']); ?>
                                    <div>
                                        
                                        <a href="#"  onclick="openModal()"
                                            section="<?php echo $program['id']; ?>"><i class="fa fa-eye"></i></a>
                                        <a href="#" class="edit-program" section="<?php echo $program['id']; ?>"><i
                                                class="fa fa-edit"></i> </a>
                                        <a href="#" class="delete-program" section="<?php echo $program['id']; ?>"><i
                                                class="fa fa-trash"></i></a>
                                    </div>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>

                    <?php
                    if (!isset($_SESSION['program_id'])) {
                        $program_id = $_SESSION['program_id'];
                    }

                    ?>
                    <h2><?php echo htmlspecialchars($program_id['program_name']) ?></h2>


                    <p>Associated Subjects:</p>
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Subject Name</th>
                        </tr>

                    </table>

                    <p>This is a simple popup window.</p>

                </div>
            </div>
            <script>
                function openModal() {
                    document.getElementById("myModal").style.display = "block";
                }

                function closeModal() {
                    document.getElementById("myModal").style.display = "none";
                }

                // Close the modal when clicking outside the content
                window.onclick = function (event) {
                    let modal = document.getElementById("myModal");
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                };
            </script>


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
                    window.history.replaceState({}, document.title, newURL);
                }, 10); */
            }
        });
    </script>


</body>

</html>