<?php
session_start();
include "../db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $studentId = $_POST['id'];
    global $pdo;

    try {

        $stmt = $pdo->prepare("DELETE FROM student_login WHERE student_id = ?");
        $stmt->execute([$studentId]);


        $stmt3 = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt3->execute([$studentId]);

        header("Location: students.php?success=Student Deleted Successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: students.php?error=Failed to Delete Student");
        exit();
    }
} else {
    echo "Invalid request.";
}
