<?php
include "../db_connect.php";

function getMyId($id)
{
    global $pdo;
    $id = intval($id);

    try {
        $sql = "SELECT s.*,
        CONCAT(s.lname, ' ', s.mname, ' ', s.fname) AS student_name
        
        FROM students s WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}



function GetSchoolYearOnProgram()
{
    global $pdo;
    $sql = "SELECT DISTINCT school_year FROM programs_with_subjects";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

function GetSchoolProgram()
{
    global $pdo;
    $sql = "SELECT DISTINCT department_program FROM programs_with_subjects";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll();
    } else {
        return [];
    }
}


function getStudentSubject()
{
    global $pdo;
    $sql = "SELECT DISTINCT * FROM student_with_subjects";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}

function InsertStudentSubject($student_id, $year, $subject_code, $subject_name, $semester, $teacher_name)
{
    global $pdo;
    $sql = "INSERT INTO student_with_subjects (student_id, school_year, subject_code, subject_name, semester, teacher_name) VALUES (?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$student_id, $year, $subject_code, $subject_name, $semester, $teacher_name]);
    if ($stmt->rowCount() > 0) {
        header('Location: subject_load.php?success=Successfully Added');
        return true;
    } else {
        header('Location: subject_load.php?error=Failed to load the Subject');
        return false;
    }
}

function GetSchoolCourse()
{
    global $pdo;
    $sql = "SELECT DISTINCT program_course FROM programs_with_subjects";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}


function GetSchoolSubject()
{
    global $pdo;
    $sql = "SELECT DISTINCT subject_name, subject_code FROM subject_with_program_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

function GetInstructorSubject()
{
    global $pdo;
    $sql = "SELECT DISTINCT teacher_name FROM subject_with_program_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}










?>