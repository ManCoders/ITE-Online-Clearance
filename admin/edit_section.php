<?php
session_start();
include "../db_connect.php";
include "./admin_function.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}


$sql = $pdo->prepare('SELECT * FROM teachers');
$sql->execute();
$teacher = $sql->fetchAll();

if (isset($_POST['submit'])) {
    $major_id = $_POST['major_id'];

    $query = "UPDATE sections SET section_name=?, teacher_id = ? WHERE id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_POST['major_name'], $_POST['teacher'], $major_id]);

    if ($stmt->rowCount() > 0) {
        header("Location: program.php?success=Section updated successfully!");
    } else {
        header("Location: program.php?error=No changes made.");
    }
    exit();
}



?>

<style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
    }

    /* Background - Match Login */
    body {
        background: #6E1313;
        background-size: cover;
        display: flex;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: rgba(255, 255, 255, 0.1);
        /* Glass effect */
        backdrop-filter: blur(10px);
        /* Blurry glass effect */
        padding: 20px;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
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
    }

    .sidebar-item {
        padding: 15px;
        margin: 10px 0;
        background: rgba(255, 255, 255, 0.2);
        /* Semi-transparent buttons */
        border-radius: 5px;
        transition: 0.3s;
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
    .dashboard-container {
        margin-left: 270px;
        /* Adjust for sidebar width */
        padding: 20px;
        width: calc(100% - 270px);
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Centers the content */
        margin-left: 200px;
    }

    /* Form Styling */
    form {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        text-align: center;

    }

    form label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
    }

    form input[type="text"] {
        width: 90%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    form input[type="submit"] {
        background-color: #ff3d00;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        margin-top: 15px;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        background-color: #ff3d00;
    }

    form input[type="button"] {
        background-color: #ff3d00;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        margin-top: 15px;
        cursor: pointer;
    }

    form input[type="button"]:hover {
        background-color: #ff3d00;
    }



    /* Success and Error Messages */
    p[align="center"] {
        font-weight: bold;
        margin-top: 10px;
    }
</style>
<div class="sidebar">
    <div class="profile-info">
        <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
    </div>
    <div class="sidebar-item">
        <a href="./index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
    </div>
    <div class="sidebar-item" style="background-color: red;">
        <a href="./Program.php"><i class="fas fa-calendar-alt"></i> Programs/Subjects</a>
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

<div class="dashboard-container">

    <form action="" method="post">
        <h2>Update Section</h2>
        <input type="hidden" name="major_id" value="<?php echo $_GET['section_id'] ?>">
        <div class="form-group">
            <label for="major_name">Section Name : </label><br><br>
            <input type="text" name="major_name" value="<?php echo $_GET['section_name'] ?>"
                placeholder="Enter subject Name" /><br><br>


            <input type="submit" name="submit" value="Update Subject">
            <input type="button" id="student-back" name="student-back" value="Back" onclick="history.back()">
        </div>
    </form>
</div>