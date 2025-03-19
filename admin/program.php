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

    if (isset($_GET['delete_section'])) {
        DeleteSectionByID($_GET['delete_section']);
    }


    $program_id = 0;


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


     .modal {
         display: none;
         /* Hidden by default */
         position: fixed;
         z-index: 1;
         left: 0;
         top: 0;
         width: 100%;
         height: 95rem;
         background-color: rgba(0, 0, 0, 0.5);
     }

     /* Modal Content */
     .modal-content {
         background-color: white;
         height: 36rem;
         margin: 1% auto;
         padding: 20px;
         border-radius: 8px;
         width: 50%;
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
                 <?php if (isset($_GET['error'])): ?>
                 <p style="color: red;"><?php echo $_GET['error']; ?></p>
                 <?php elseif (isset($_GET['success'])): ?>
                 <p style="color: green;"><?php echo $_GET['success']; ?></p>
                 <?php endif; ?>
                 <form action="" method="post">
                     <input type="text" name="id" value="<? $_GET['program_id'] ?>" hidden>
                     <label style="margin: 2px;" for=" program_id">Course Title:</label>

                     <?php $teacher = GetCourse(); ?>
                     <select style="height: 2.4rem; width:100%; margin-top:5px;" name="course" id="course" required>
                         <option value="" disabled>Select Teacher</option>
                         <?php foreach ($teacher as $t) { ?>
                         <option value="<?php echo $t['program_name']; ?>"><?php echo $t['program_name']; ?>
                         </option>
                         <?php } ?>
                     </select>
                     <label for="department">Programs :</label>
                     <select
                         style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                         name="department" required>
                         <option value="">Select Program</option>
                         <option value="DT">DT</option>
                         <option value="AIT">AIT</option>
                         <option value="TITE">TITE</option>
                     </select>
                     <label for="sy">School Year :</label>
                     <select
                         style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                         name="sy" required>
                         <option value="">Select School Year</option>
                         <option value="2022-2023">2022-2023</option>
                         <option value="2023-2024">2023-2024</option>
                         <option value="2024-2025">2024-2025</option>
                         <option value="2025-2026">2025-2026</option>
                     </select>
                     <input type="submit" name="program_id" value="Add Program">
                 </form>
             </div>

             <div class="card_table">
                 <h2>Programs</h2>

                 <form method="GET">
                     <label for="sy">School Year:</label>
                     <select name="sy"
                         style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                         <option value="">Select School Year</option>
                         <?php
                            $years = ["2022-2023", "2023-2024", "2024-2025", "2025-2026"];
                            foreach ($years as $year) {
                                $selected = (isset($_GET['sy']) && $_GET['sy'] == $year) ? 'selected' : '';
                                echo "<option value='$year' $selected>$year</option>";
                            }
                            ?>
                     </select>

                     <label for="department">Programs:</label>
                     <select name="department"
                         style="height: 2.4rem; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                         <option value="">Select Program</option>
                         <?php
                            $departments = ["DT", "AIT", "TITE"];
                            foreach ($departments as $dept) {
                                $selected = (isset($_GET['department']) && $_GET['department'] == $dept) ? 'selected' : '';
                                echo "<option value='$dept' $selected>$dept</option>";
                            }
                            ?>
                     </select>

                     <button
                         style="height: 2.4rem; background-color: #ff3d00; margin: 5px; width: 50%; color: #ffff; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"
                         type="submit" class="btn btn-primary" style="margin-top: 10px; ">Submit</button>
                 </form>
                 <div class="tables" style="background-color: #8B0000; height: auto; color: white;">
                     <table class="table table-striped table-bordered">
                         <tbody>
                             <?php
                                $list = GetProgramList();
                                foreach ($list as $index => $program) {
                                    // Apply filter conditions
                                    if (
                                        (!isset($_GET['sy']) || $_GET['sy'] == '' || $_GET['sy'] == $program['school_year']) &&
                                        (!isset($_GET['department']) || $_GET['department'] == '' || $_GET['department'] == $program['department_program'])
                                    ) {
                                ?>
                             <tr>
                                 <td>
                                     <?php echo ($index + 1) . '. ' . htmlspecialchars($program['school_year']) . ' - ' . htmlspecialchars($program['department_program']) . ' - ' . htmlspecialchars($program['program_course']); ?>
                                     <div>
                                         <a
                                             href="view_program.php?program_id=<?php echo $program['id']; ?>&program_name=<?php echo urlencode($program['department_program']); ?>&course_name=<?php echo urlencode($program['program_course']); ?>&school_year=<?php echo urlencode($program['school_year']); ?>&subject_name=<?php echo urlencode($program['subject_name']); ?>">
                                             <i class="fa fa-eye"></i>
                                         </a>
                                         <a class="edit-program" section="<?php echo $program['id']; ?>"><i
                                                 class="fa fa-edit"></i></a>
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



                 <div id="myModal" class="modal">
                     <div class="modal-content">

                         <span class="close" onclick="closeModal()">&times;</span>
                         <!--  -->
                         <h2 id="programTitle">Program Name</h2>
                         <span id="sy">SY: 2024-2025</span>
                         <p class="semester">1st Semester</p>

                         <div class="table_content">
                             <table>
                                 <tr>
                                     <th>#</th>
                                     <th>Subject Code</th>
                                     <th>Subject Name</th>
                                     <th>Actions</th>
                                 </tr>
                                 <tbody id="subjectList1">
                                     <tr>
                                         <td>1</td>
                                         <td>Subject Code</td>
                                         <td>Subject Names</td>
                                         <td>
                                             <button class="btn btn-secondary edit-subject"><i
                                                     class="fa fa-edit"></i></button>
                                             <button class="btn btn-danger delete-subject"><i
                                                     class="fa fa-trash"></i></button>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>

                         </div>

                         <!-- <form action="">
                        <div class="add_subject">
                            <input type="text" name="program_id" id="program_id"></input>
                            <input type="text" name="semester_id" id="semester_id"></input>
                            <td> <input type="text" id="subject_code_input" placeholder="Enter Subject Code">
                            </td>
                            <td> <input type="text" id="subject_name_input" placeholder="Enter Subject Name">
                            <td>
                                <input type="submit" id="add-subject1" value="Add" class="btn btn-primary add-subject">
                                </input>
                            </td>
                        </div>
                    </form> -->

                         <p class="semester">2nd Semester</p>
                         <div class="table_content">
                             <table>
                                 <tr>
                                     <th>#</th>
                                     <th>Subject Code</th>
                                     <th>Subject Name</th>
                                     <th>Actions</th>
                                 </tr>

                                 <tbody id="subjectList2">
                                     <tr>
                                         <td>1</td>
                                         <td>Sample Subject Code</td>
                                         <td>Sample Subject Name</td>
                                         <td>
                                             <button class="btn btn-secondary edit-subject"><i
                                                     class="fa fa-edit"></i></button>
                                             <button class="btn btn-danger delete-subject"><i
                                                     class="fa fa-trash"></i></button>
                                         </td>
                                     </tr>

                                 </tbody>
                             </table>

                         </div>
                         <p class="semester">New Subjects</p>
                         <form action="">
                             <div class="add_subject">
                                 <input type="text" name="program_id" id="program_id" hidden></input>

                                 <select style="height: 2.4rem; margin-top:5px;" name="semester_id" id="semester_id">
                                     <option value="" selected disabled>Select Semester</option>
                                     <option value="1">1st Semester</option>
                                     <option value="2">2nd Semester</option>
                                 </select>
                                 <td> <input type="text" id="subject_code_input" placeholder="Enter Subject Code">
                                 </td>
                                 <td> <input type="text" id="subject_name_input" placeholder="Enter Subject Name">
                                 <td>
                                     <input type="submit" id="add-subject" value="Add"
                                         class="btn btn-primary add-subject">
                                     </input>
                                 </td>
                             </div>
                         </form>
                     </div>
                 </div>

                 <script>
                 document.getElementById('program_id').addEventListener('input', handleInput);
                 document.getElementById('semester_id').addEventListener('input', handleInput);

                 function handleInput(event) {
                     const {
                         id,
                         value
                     } = event.target;
                     console.log(`The current value of ${id} is: ${value}`);
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

                 window.onclick = function(event) {
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
         document.addEventListener("DOMContentLoaded", function() {

             // Deleting Program
             document.querySelectorAll(".delete-program").forEach(button => {
                 button.addEventListener("click", function(event) {
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
                 button.addEventListener("click", function(event) {
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
                                     window.history.replaceState({ }, document.title, newURL);
                 }, 10); */
             }


         });
         </script>


 </body>

 </html>