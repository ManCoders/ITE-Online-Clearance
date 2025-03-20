<?php
include "../db_connect.php";

if (isset($_POST['action'])) {
    global $pdo;

    if ($_POST['action'] == 'getPrograms' && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];

        // Ensure Student ID is numeric to prevent SQL injection
        if (!is_numeric($student_id)) {
            echo "<option value=''>Invalid Student ID</option>";
            exit;
        }

        $stmt = $pdo->prepare("SELECT DISTINCT program, course FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($programs) {
            foreach ($programs as $program) {
                echo "<option selected value='{$program['program']}'>{$program['program']}- {$program['course']}</option>";
            }
        } else {
            echo "<option value=''>No programs found</option>";
        }
    }


}
?>