<?php
include "../db_connect.php";


function getStudentLogins()
{
    global $pdo;
    $sql = "SELECT s.*, sl.*
            FROM students s
            JOIN student_login sl ON s.id = sl.student_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

/* function getSubjects()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT *
            FROM subject_year
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
    }
} */


function insertSubject($school_year, $subject_id, $Semester_id)
{
    global $pdo;

    $stml = $pdo->prepare("
    INSERT INTO student_subject 
    
    ");
}

function getSubjects2()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT sy.name, sy.code, sy.id AS subject_id, s.id AS semester_id, s.semester, sub.id AS program_id, t.id AS teacher_id, t.lname, t.fname, t.mname, y.id AS year_id
            FROM subject_year sy 
            INNER JOIN teachers t ON sy.teacher_id = t.id 
            INNER JOIN school_year y ON sy.year_id = y.id 
            INNER JOIN semesters s ON sy.semester_id = s.id 
            INNER JOIN programs sub ON sy.program_id = sub.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
    }
}

function GetSemester2()
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT *
        FROM Semesters
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





function getYearSubject2()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT sy.id AS subject_id, sy.name, sy.code,
            sm.id AS semester_id, sm.semester,
            sy2.id AS school_year_id, sy2.school_year,
            s.id AS section_id, s.section_name,
            p.id AS program_id, p.program_name,
            t.id AS teacher_id,CONCAT(t.fname, ' ', t.mname, ' ', t.lname) AS teacher_full_name

            FROM subject_year sy
            INNER JOIN semesters sm ON sy.semester_id = sm.id
            INNER JOIN school_year sy2 ON sy.year_id = sy2.id
            INNER JOIN sections s ON sy.section_id = s.id
            INNER JOIN programs p ON sy.program_id = p.id
            INNER JOIN teachers t ON sy.teacher_id = t.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
    }
}

function getYearSubjectStudent()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT sy.id AS subject_id, sy.name, sy.code,
            sm.id AS semester_id, sm.semester,
            sy2.id AS school_year_id, sy2.school_year,
            s.id AS section_id, s.section_name,
            p.id AS program_id, p.program_name,
            t.id AS teacher_id,CONCAT(t.fname, ' ', t.mname, ' ', t.lname) AS teacher_full_name

            FROM subject_year sy
            INNER JOIN semesters sm ON sy.semester_id = sm.id
            INNER JOIN school_year sy2 ON sy.year_id = sy2.id
            INNER JOIN sections s ON sy.section_id = s.id
            INNER JOIN programs p ON sy.program_id = p.id
            INNER JOIN teachers t ON sy.teacher_id = t.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
    }
}


function load_subject($sy, $semester_id, $subject_id, $student_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        UPDATE student_subject SET subject_id = ?, semester_id = ?, teacher_id = ? WHERE student_id = ? OR student_code = ?
        ");
        $stmt->execute([$subject_id, $sy, $student_id, $student_id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("error" . $e->getMessage());
        return [];
    }
}

function GetSchoolYear2()
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT *
        FROM school_year
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function GetPrograms2()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM programs");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function GetTeachers()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM teachers");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function GetSchoolYearById($school_year)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT s.school_year FROM subject_year sy 
        
        INNER JOIN school_year s ON sy.year_id = s.id
        WHERE year_id = ?
        
        ");
        $stmt->execute([$school_year]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}


function getStudentName($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT 
        CONCAT(lname, ' ', fname, ' ', mname) AS full_name
     FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
    }
}

function GetProgramById($program_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT p.program_name FROM subject_year sy 
        
        INNER JOIN programs p ON sy.program_id = p.id
        WHERE program_id = ?
        
        ");
        $stmt->execute([$program_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}


function GetSemesterById($semester_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT s.semester FROM subject_year sy 
        
        INNER JOIN semesters s ON sy.semester_id = s.id
        WHERE semester_id = ?
        
        ");
        $stmt->execute([$semester_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}

function GetSectionById($section_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT s.section_name FROM subject_year sy 
        
        INNER JOIN sections s ON sy.section_id = s.id
        WHERE section_id = ?
        
        ");
        $stmt->execute([$section_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}


function GetInstructorById($instructor_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT 
        
        CONCAT(s.lname, ' ', s.mname, ' ', s.fname) AS instructor_name
        
        FROM subject_year sy 
        
        INNER JOIN teachers s ON sy.teacher_id = s.id
        WHERE teacher_id = ?
        
        ");
        $stmt->execute([$instructor_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}

function getYearSubjectStudent2($student_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("

           SELECT ss.id, ss.clearance, ss.remark, ss.finalterm,
           sy.id AS subject_id, sy.name, sy.code, sy.semester_id, sy.section_id, sy.year_id, sy.teacher_id,
           s.id AS student_id, CONCAT(s.lname, ' ', s.mname, ' ', s.fname) AS student_complete_name
           FROM student_subjects ss
           
           INNER JOIN subject_year sy ON ss.subject_id = sy.id 
           INNER JOIN students s ON ss.student_id = s.id
           WHERE ss.student_id = ?
        ");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
    }
}


function getStudentSubjectsById($student_id)
{
    global $pdo;


    try {
        $stmt = $pdo->prepare("SELECT s.*, sub.*,  t.lname, t.fname, t.mname
          FROM student_subjects s 
          INNER JOIN subjects sub ON s.subject_id = sub.id 
          INNER JOIN teachers t ON sub.teacher_id = t.id 
          
          WHERE s.student_id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}


