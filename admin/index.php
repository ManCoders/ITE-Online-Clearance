<?php
session_start();
include "../db_connect.php";

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

$sampleData = [
    'sections' => [],
    'programs' => [],
    'subjects' => [],
    'students' => [],
    'teachers' => []
];



$sections_query = "SELECT * FROM sections";
$section_result = $pdo->prepare($sections_query);
$section_result->execute();
if ($section_result->rowCount() > 0) {
    while ($row = $section_result->fetch()) {
        $sampleData['sections'][] = $row['section_name']; // Renamed key
    }
}


$programs_query = "SELECT * FROM programs_with_subjects";
$programs_result = $pdo->prepare($programs_query);
$programs_result->execute();
if ($programs_result->rowCount() > 0) {
    while ($row = $programs_result->fetch()) {
        $sampleData['programs'][] = $row['program_course'];
    }
}



$subjects_query = "SELECT DISTINCT subject_name FROM subject_with_program_id";
$subjects_result = $pdo->prepare($subjects_query);
$subjects_result->execute();
if ($subjects_result->rowCount() > 0) {
    while ($row = $subjects_result->fetch()) {
        $sampleData['subjects'][] = $row['subject_name'];
    }
}


$students_query = "SELECT s.* , sl.* FROM student_login sl INNER JOIN students s ON sl.student_id = s.id";
$students_result = $pdo->prepare($students_query);
$students_result->execute();
if ($students_result->rowCount() > 0) {
    while ($row = $students_result->fetch()) {
        $new['new'] = $row['lname'] . "," . $row['fname'] . " " . $row['mname'] . " - " . $row['email'];
        $sampleData['students'][] = $new['new'];
    }
}

$teachers_query = "SELECT s.*, sl.* FROM teacher_login sl INNER JOIN teachers s ON sl.teacher_id = s.id";
$teachers_result = $pdo->prepare($teachers_query);
$teachers_result->execute();
if ($teachers_result->rowCount() > 0) {
    while ($row = $teachers_result->fetch()) {
        $new['new'] = $row['lname'] . "," . $row['fname'] . " " . $row['mname'] . " - " . $row['email'];
        $sampleData['teachers'][] = $new['new'];
    }
}

$totals = [

    'programs' => count($sampleData['programs']),
    'subjects' => count($sampleData['subjects']),
    'students' => count($sampleData['students']),
    'teachers' => count($sampleData['teachers'])
];

?>
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

    /* Dashboard Container */
    .dashboard-container {
        margin-left: 270px;
        padding: 20px;
        width: calc(100% - 270px);
    }

    /* Stats Grid */
    .left-content {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 20px;
    }

    .stat-box {
        flex: 1;
        background: rgba(255, 255, 255, 0.2);
        /* Light glass effect */
        padding: 20px;
        text-align: center;
        border-radius: 8px;
        min-width: 180px;
        cursor: pointer;
        transition: 0.3s;
        color: white;
    }

    .stat-box:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    /* Right Content (Lists) */
    .right-content {

        padding: 20px;
        border-radius: 10px;
    }

    /* List Container */
    .list-container {
        background: rgba(255, 255, 255, 0.2);
        padding: 30px;
        border-radius: 10px;
        display: none;
        position: relative;
        margin-bottom: 20px;
    }

    .list-container h3 {
        text-align: center;
        margin-bottom: 10px;
        color: white;
    }

    .ul-items {
        list-style: none;
        padding: 0;
    }

    .ul-items li {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .ul-items li:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    /* Orange Buttons (To Match Login Page) */
    .orange-button {
        background-color: #ff3d00;
        /* Bright Orange */
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
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

    /* Close Button */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
    }

    .close-btn:hover {
        color: red;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .dashboard-container {
            margin-left: 210px;
            width: calc(100% - 210px);
        }

        .left-content {
            flex-direction: column;
        }

        .stat-box {
            min-width: 100%;
        }
    }
</style>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    </link>
</head>
<div class="sidebar">
    <div class="profile-info">
        <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
    </div>
    <div class="sidebar-item" style="background-color: red;">
        <a href="./index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
    </div>
    <div class="sidebar-item">
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


<div class="dashboard-container">

    <div class="left-content">
        <?php foreach ($totals as $category => $count): ?>
            <div class="stat-box" onclick="showList('<?php echo $category; ?>')">
                <h2><?php echo ucfirst($category); ?></h2>
                <p><?php echo $count; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="right-content">
        <?php foreach ($sampleData as $category => $items): ?>
            <div id="list-<?php echo $category; ?>" class="list-container" style="display: block;">
                <span class="close-btn" onclick="hideList('<?php echo $category; ?>')">&times;</span>
                <h3><?php echo ucfirst($category); ?> List</h3>
                <ul class="ul-items">
                    <?php foreach ($items as $index => $item): ?>
                        <li>
                            <?php echo $item; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>


</div>


<script>
    function showList(category) {
        var lists = document.getElementsByClassName('list-container');
        for (var i = 0; i < lists.length; i++) {
            lists[i].style.display = 'none';
        }
        document.getElementById('list-' + category).style.display = 'block';
    }

    function hideList(category) {
        document.getElementById('list-' + category).style.display = 'none';
    }

    /*
        function editItem(category, item) {
            let newName = prompt("Edit " + category + " name:", item);
            if (newName !== null && newName.trim() !== "") {
                window.location.href = `edit_item.php?category=${category}&oldName=${encodeURIComponent(item)}&newName=${encodeURIComponent(newName)}`;
            }
        }
    
        function deleteItem(category, item) {
            if (confirm("Are you sure you want to delete " + item + " from " + category + "?")) {
                window.location.href = `delete_item.php?category=${category}&item=${encodeURIComponent(item)}`;
            }
        } */
</script>