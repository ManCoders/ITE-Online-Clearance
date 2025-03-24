<?php
include "../db_connect.php";


function getTeacherLoginsById($student_id)
{
    global $pdo;
    $sql = "SELECT s.*, sl.*
            FROM teachers s
            INNER JOIN teacher_login sl ON s.id = sl.teacher_id
            WHERE s.id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_id]);
    return $stmt->fetch();
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
function getEmailByStudentId($student_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT email FROM student_login WHERE id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
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

function getStudentDetailsById($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT *,CONCAT(lname, ' ', mname, ' ', fname) AS student_name FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : [];
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}




function updateStudent($subject_id, $remark, $status, $final)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE student_with_subjects SET remark = ?, status = ?, final = ?  WHERE id = ?");
        $stmt->execute([$remark, $status, $final, $subject_id]);
        header("Location: students.php?success=Record updated successfully.");
        exit();
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        header("Location: update_student.php?error=Database error: " . $e->getMessage());
        exit();
    }
}
function getMyId2($id)
{
    global $pdo;
    $id = intval($id);

    try {
        $sql = "SELECT s.*,
        CONCAT(s.lname, ' ', s.mname, ' ', s.fname) AS teacher_name
        
        FROM teachers s WHERE s.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}



function GetTeacherById2($teacher_id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([$teacher_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array
    } catch (PDOException $e) {
        return false; // Return false if there's an error
    }
}



function GetStudentByTeacherId($teacher_id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare(" 
        SELECT s.id AS student_id, s.student_code, s.fname, s.mname, s.lname, s.contact, s.profile,
               ss.id AS student_subject_id, sy.section_id, ss.subject_id, ss.remark, ss.finalterm
        FROM student_subjects ss
        INNER JOIN students s ON ss.student_id = s.id
        INNER JOIN subject_year sy ON ss.subject_id = sy.id
        WHERE ss.teacher_id = ? AND ss.subject_id = ?

        ");

        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; // Return empty if error occurs
    }
}


function getStudentSubjectByid($teacher_id, $subject_id, $student_id)
{
    global $pdo;
    $sql = "SELECT * FROM student_with_subjects WHERE teacher_id = ? AND id = ? AND student_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_id, $subject_id, $student_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}



function getSubjectById2($id)
{
    global $pdo;
    $sql = "SELECT DISTINCT teacher_id, student_id FROM student_with_subjects WHERE teacher_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}


function getTotalStudentByTeacherIdansStudentId($teacher_id)
{
    global $pdo;
    $sql = "SELECT DISTINCT  COUNT(sws.student_id) as student_count
            FROM student_with_subjects sws 
            WHERE sws.teacher_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_id]);
        echo $stmt->fetchColumn();
        return $stmt->fetch();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}
function getSubjectTeacherDashboard($id)
{
    global $pdo;
    $sql = "SELECT DISTINCT sws.subject_code, sws.subject_name
        FROM student_with_subjects sws 
        WHERE sws.teacher_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}

function getSubjectTeacher($id)
{
    global $pdo;
    $sql = "SELECT DISTINCT sws.*
            FROM student_with_subjects sws 
            WHERE sws.teacher_id = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}

function getSubjectList($teacher_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT DISTINCT ss.subject_id, ss.id, ss.student_id, ss.student_id,
        sy.section_id, sy.name, sy.code, sy.teacher_id
        FROM student_subjects ss
        INNER JOIN subject_year sy ON ss.subject_id = sy.id
        WHERE ss.teacher_id = ?
        ");
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}


function getStudentById($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT CONCAT(lname, ' ', mname, ' ', fname) AS student_name , email, course FROM students WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}

function getSection($section_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
        SELECT * FROM sections WHERE id = ?
        ");
        $stmt->execute([$section_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}

function GetStudentsList($searchQuery = "")
{
    global $pdo;

    $sql = "
    SELECT t.*, t.id AS student_id, tl.*, t.contact, tl.email, tl.student_id AS tloginId 
    FROM student_login tl 
    INNER JOIN students t ON tl.student_id = t.id
    ";

    // If there is a search query, modify the SQL query
    if (!empty($searchQuery)) {
        $sql .= " WHERE t.student_code LIKE :search 
                  OR t.fname LIKE :search 
                  OR t.lname LIKE :search";
    }

    $stmt = $pdo->prepare($sql);

    // Bind search parameter if search query exists
    if (!empty($searchQuery)) {
        $searchWildcard = "%$searchQuery%";
        $stmt->bindParam(":search", $searchWildcard, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function deleteStudentSubjectById($student_id, $subject_id, $teacher_id)
{
    global $pdo; // Make sure you have your database connection

    try {
        $stmt = $pdo->prepare("DELETE FROM student_subjects WHERE student_id = ? AND subject_id = ? AND teacher_id = ?");
        $stmt->execute([$student_id, $subject_id, $teacher_id]);

        if ($stmt->rowCount() > 0) {
            header("Location: students.php?success=Student successfully removed.");
        } else {
            header("Location: students.php?error=No record found to delete.");
        }
        exit();
    } catch (PDOException $e) {
        header("Location: students.php?error=Database error: " . $e->getMessage());
        exit();
    }
}

function GetProgramById2($program_id)
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

function getSubjectViewByStudentId2($teacher_id, $student_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT ss.id AS subject_id,
            sy.year_id, sy.program_id, sy.semester_id, sy.code, sy.name, sy.teacher_id, sy.section_id,
            ss.finalterm, ss.remark, ss.clearance
            FROM student_subjects ss
            INNER JOIN subject_year sy ON ss.subject_id = sy.id
            WHERE ss.student_id = ? AND ss.teacher_id = ?
        ");
        $stmt->execute([$student_id, $teacher_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}

function CheckStudentCode($studentId)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_code = ?");
        $stmt->execute([$studentId]);

        $result = $stmt->fetch();
        if ($result) {
            header('location: students.php?error1=Duplicate Error, Please Use Unique Subject Name and Code');
            return true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    return false;
}

function StudentNonAct()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM students");
        $stmt->execute();
        return $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}


function InsertNewStudentByID($studentID, $lname, $mname, $fname, $contact)
{
    global $pdo;
    try {
        $sql = $pdo->prepare("INSERT INTO students (student_code, lname, mname, fname, contact) VALUES (?, ?, ?, ?, ?)");

        if ($sql->execute([$studentID, $lname, $mname, $fname, $contact])) {
            $sql = $pdo->prepare("INSERT INTO student_subjects (student_code) VALUES (?)");
            $sql->execute([$studentID]);

            header('location: students.php?success=Student added successfully');
            exit();
        } else {

            header('location: students.php?error=Student added Unsuccessfully');
            exit();
        }
    } catch (Throwable $th) {
        $_SESSION["error"] = "Failed Registration!";
    }
}


function DeleteStudentByID2($student_code, $teacher_id)
{
    global $pdo;

    try {
        $stmt3 = $pdo->prepare("DELETE FROM student_subjects WHERE student_id = ? AND teacher_id = ?");
        $stmt3->execute([$student_code, $teacher_id]);

        /* $stmt4 = $pdo->prepare("DELETE FROM student_login WHERE id = ?");
        $stmt4->execute([$loginid]);
        */
        header("Location: students.php?success=Student Deleted Successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: students.php?error=Failed to Delete Student");
        exit();
    }
}

function AutoStudentID()
{
    global $pdo;

    do {
        $year = date("Y");
        $randomNumber = mt_rand(1000, 9999);
        $studentID = "S" . $year . $randomNumber;

        $stmt = $pdo->prepare("SELECT student_code FROM students WHERE student_code = ?");
        $stmt->execute([$studentID]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    return $studentID;
}


function getYourStudents($teacher_id)
{
    global $pdo;
    try {
        $sql = "SELECT ss.*, s.lname, s.mname, s.fname, sb.subject_name
                FROM student_subjects ss
                INNER JOIN students s ON ss.student_id = s.id
                INNER JOIN subjects sb ON ss.subject_id = sb.id
                WHERE ss.teacher_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}



function GetAllStudent($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT t.*, s.section_name AS section FROM sections s
    INNER JOIN teachers t ON s.teacher_id = t.id
       WHERE teacher_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function GetAllStudentSubject($teacher_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT *
          FROM student_subjects s
          WHERE s.teacher_id = ?");
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function getSubjectsById($student_id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT s.*, sub.*, ss.lname, ss.fname, ss.mname
          FROM student_subjects s 
          INNER JOIN subjects sub ON s.subject_id = sub.id 
          INNER JOIN students ss ON s.student_id = ss.id
          INNER JOIN teachers t ON sub.teacher_id = t.id 
          WHERE s.teacher_id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
