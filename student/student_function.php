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

    return $stmt->fetchAll();
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
    $sql = "SELECT DISTINCT subject_name FROM subject_with_program_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}










?>