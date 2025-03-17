<?php
include "../db_connect.php";


if (!isset($_SESSION['teacher_id'])) {
    header('location: ../index.php');
    exit();
}


$student_id = $_SESSION["teacher_id"];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


<!-- <div class="dashboard-container">
    <?php
    $students = getTeacherLoginsById($_SESSION["teacher_id"]);
    if ($students): ?>
        <div align="center" class="profile-container">
            <h3 class="profile-title">Profile Information</h3>
            <div class="profile-info">
                <img src="../images/ITE.png" alt="Profile Icon" class="profile-icon">
                <div class="profile-details">
                    <h3 class="profile-name">Name: <?= htmlspecialchars($students['lname']); ?>,
                        <?= htmlspecialchars($students['fname']); ?>
                        <?= htmlspecialchars($students['mname'][0]); ?>.
                    </h3>
                    <h3 class="profile-email">Email: <i><?= htmlspecialchars($students['email']); ?></i></h3>
                    <p class="profile-email">Account Status: <?= htmlspecialchars($students['verify']); ?></p>
                </div>
            </div>
        </div>

    <?php else: ?>
        <p>Student details not found.</p>
    <?php endif; ?>

</div> -->