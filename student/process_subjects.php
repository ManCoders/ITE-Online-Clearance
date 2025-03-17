<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['student_id'])) {
    header('location: ../index.php');
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['selected_subjects'])) {
        header("Location: subject_load.php?error=No subjects selected.");
        exit();
    }

    $selectedSubjects = $_POST['selected_subjects'];

    try {
        $pdo->beginTransaction();

        foreach ($selectedSubjects as $subjectId) {
            // Check if the subject is already registered
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM student_subjects WHERE subject_id = ? AND student_id = ?");
            $checkStmt->execute([$subjectId, $student_id]);
            $count = $checkStmt->fetchColumn();

            $teacher_in = $pdo->prepare("SELECT * FROM subject_year WHERE id = ?");
            $teacher_in->execute([$subjectId]);
            $teacher_in = $teacher_in->fetch();

            if ($count == 0) {
                // If not already enrolled, insert the subject
                /* x */

                $stmt = $pdo->prepare("INSERT INTO student_subjects (subject_id, teacher_id, student_id) VALUES (?,?,?)");
                $stmt->execute([$subjectId, $teacher_in['teacher_id'], $student_id]);
            } else {
                // If already enrolled, update the enrollment status
                header("Location: subject_load.php?error=Subject already enrolled.");
                exit();
            }
        }

        $pdo->commit();
        header("Location: subject_load.php?success=Subjects added successfully!");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: subject_load.php?error=Error adding subjects.");
        exit();
    }
} else {
    header("Location: subject_load.php");
    exit();
}
?>