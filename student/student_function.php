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

function getStudentByIds($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT school_year FROM students WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: [];
    } catch (PDOException $e) {
        return [];
    }
}


function getSubject2($id)
{
    global $pdo;
    $sql = "SELECT * FROM student_with_subjects WHERE student_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
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


function deleteStudentSubjectbyId22($subject_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM student_with_subjects WHERE id = ?");
        $stmt->execute([$subject_id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {

        echo 'Error: ' . $e->getMessage();
    }
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


function getStudentSubject($student_id)
{
    global $pdo;
    $sql = "SELECT DISTINCT * FROM student_with_subjects WHERE student_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}

function InsertStudentSubject($program, $student_id, $year, $subject_code, $subject_name, $semester, $teacher_name)
{
    global $pdo;

    try {
        // Check if the subject already exists for the student
        $sql = "SELECT * FROM student_with_subjects WHERE student_id = ? AND subject_code = ? AND school_year = ? AND teacher_id = ? AND semester=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id, $subject_code, $year, $teacher_name, $semester]);

        if ($stmt->rowCount() > 0) {
            header('Location: subject_load.php?error=Subject already exists');
            exit();
        }

        // Insert new subject for the student
        $sql = "INSERT INTO student_with_subjects (program_id, student_id, school_year, subject_code, subject_name, semester, teacher_id) 
                VALUES (?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$program, $student_id, $year, $subject_code, $subject_name, $semester, $teacher_name]);

        if ($stmt->rowCount() > 0) {
            header('Location: subject_load.php?success=Successfully Added');
            exit();
        } else {
            header('Location: subject_load.php?error=Failed to load the Subject');
            exit();
        }

    } catch (PDOException $e) {
        // Handle database errors
        header('Location: subject_load.php?error=Database Error: ' . $e->getMessage());
        exit();
    }
}

function getTeacherById($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare(" SELECT id, CONCAT(lname, ' ',mname, ' ',fname) AS teacher_name FROM teachers WHERE id = ? ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
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