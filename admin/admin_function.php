<?php
include "../db_connect.php";


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

function AutoTeacherID()
{
    global $pdo;

    do {
        $year = date("Y");
        $randomNumber = mt_rand(1000, 9999);
        $studentID = "T" . $year . $randomNumber;

        $stmt = $pdo->prepare("SELECT teacher_code FROM teachers WHERE teacher_code = ?");
        $stmt->execute([$studentID]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    return $studentID;
}

function GetStudentsList()
{
    global $pdo;
    $stmt = $pdo->prepare("
    SELECT t.*, tl.*,t.contact, tl.email, tl.student_id AS tloginId 
    
    FROM student_login tl 
    INNER JOIN students t ON tl.student_id = t.id
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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


function TeacherList()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, CONCAT(fname, ' ', mname, ' ', lname) AS teacher_name FROM teachers");
        $stmt->execute();
        return $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}

function GetSubject()
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT *
        FROM subjects
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function GetSemester()
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT *
        FROM Semesters
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function GetSchoolYear()
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT *
        FROM school_year
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function GetCourse()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM course");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function GetPrograms()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM programs");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function teacher_profession()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM teacher_profession ORDER BY profession ASC");
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

function GetStudents()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT s.*, 
            CONCAT(s.lname, ' ', s.mname, ' ', s.fname) AS student_name
         FROM students s

         ");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $students;
    } catch (PDOException $e) {
        return [];
    }
}

/* 
function GetTeachersByName()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE ");
    $stmt->execute();
    return $stmt->fetchAll();
} */
function GetMajorList()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM majors");
    $stmt->execute();
    return $stmt->fetchAll();
}


function GetSectionList()
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT *
        FROM sections
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function GetMinorList()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT t.lname, t.fname, s.*, sm.semester  FROM subjects s 
    INNER JOIN teachers t ON s.teacher_id = t.id
    INNER JOIN semesters sm ON s.semester_id = sm.id
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}


function getYearSubject()
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

function getSubjectViewById($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT sy.name, sy.code, sy.id AS subject_id, s.id AS semester_id, s.semester, sub.id AS program_id, sub.program_name, t.id AS teacher_id, t.lname, t.fname, t.mname, y.id AS year_id, y.school_year
            FROM subject_year sy 
            INNER JOIN teachers t ON sy.teacher_id = t.id 
            INNER JOIN school_year y ON sy.year_id = y.id 
            INNER JOIN semesters s ON sy.semester_id = s.id 
            INNER JOIN programs sub ON sy.program_id = sub.id WHERE sy.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return []; // Return an empty array on failure
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

function getSubjectViewByStudentId($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT ss.id AS subject_id,

            sy.year_id, sy.program_id, sy.semester_id, sy.code, sy.name, sy.teacher_id, sy.section_id,
            ss.finalterm, ss.remark, ss.clearance

            FROM student_subjects ss
            INNER JOIN subject_year sy ON ss.subject_id = sy.id
            WHERE ss.student_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}



function update_subject($sy, $subject_id, $subject_name, $subject_code, $semester, $program, $teacher_id)
{
    global $pdo;

    try {
        $sql = "UPDATE subject_year SET 
                    name = ?, 
                    code = ?, 
                    semester_id = ?, 
                    program_id = ?, 
                    teacher_id = ?, 
                    year_id = ? 
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_name, $subject_code, $semester, $program, $teacher_id, $sy, $subject_id]);
    } catch (PDOException $e) {
        die("Error updating subject: " . $e->getMessage());
    }
}


function deleteSubjectbyId231($subject_id)
{
    global $pdo;
    try {
        $sql = "DELETE FROM subject_with_program_id WHERE id = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_id]);
        header("Location: view_program.php?subject_id=" . $subject_id . "&status=success&message=Subject deleted successfully");
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function deleteSubjectbyId($subject_id, $program_id)
{
    global $pdo;
    try {
        $sql = "DELETE FROM subject_with_program_id WHERE id = ? AND program_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_id, $program_id]);
        header("Location: view_program.php?subject_id=" . $subject_id . "&program_id=" . $program_id . "&status=success&message=Subject deleted successfully");
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}


function editSubjectById($teacher_id, $subject_id, $program_id, $subject_name, $subject_code, $semester)
{
    global $pdo;
    try {
        $sql = "SELECT teacher_name FROM student_with_subjects WHERE program_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$program_id]);

        $teacher_name_update = $stmt->fetch(PDO::FETCH_ASSOC);




        $sql = "UPDATE student_with_subjects SET teacher_id = ?,  teacher_name = ? WHERE subject_name = ? AND subject_code = ? AND semester = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_id, $teacher_name_update, $subject_name, $subject_code, $semester]);

        $sql = "UPDATE subject_with_program_id SET teacher_id = ?, subject_name = ?, subject_code = ?, semester =
        ?, teacher_name = ? WHERE id = ? AND program_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_id, $subject_name, $subject_code, $semester, $teacher_name_update, $subject_id, $program_id]);
        $subject_ids = $pdo->lastInsertId();




        header("Location: view_program.php?subject_id=" . $subject_id . "&program_id=" . $program_id . "&status = success&message = Subject updated successfully");
        return true;
    } catch (PDOException $e) {

        return $e->getMessage();
    }

}


function GetProgramList()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM programs_with_subjects");
    $stmt->execute();
    return $stmt->fetchAll();
}




function DeleteStudentByID($student_code)
{
    global $pdo;

    try {

        $stmt = $pdo->prepare("DELETE FROM student_login WHERE student_id = ?");
        $stmt->execute([$student_code]);


        $stmt3 = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt3->execute([$student_code]);

        header("Location: students.php?success=Student Deleted Successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: students.php?error=Failed to Delete Student");
        exit();
    }
}

function DeleteTeacherWithId($student_code)
{
    global $pdo;

    try {

        $stmt = $pdo->prepare("DELETE FROM teacher_login WHERE teacher_id = ?");
        $stmt->execute([$student_code]);


        $stmt3 = $pdo->prepare("DELETE FROM teachers WHERE id = ?");
        $stmt3->execute([$student_code]);

        header("Location: teachers.php?success=Student Deleted Successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: teachers.php?error=Failed to Delete Student");
        exit();
    }
}

function DeleteMinorByID($minorid)
{
    global $pdo;
    $query = "DELETE FROM subjects WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$minorid])) {
        header("Location: program.php?success=Minor Subject Deleted Successfully");
        exit();
    } else {
        header("Location: program.php?error=Failed to Delete Minor Subject");
        exit();
    }
}
function getSubjectById($id)
{
    try {
        global $pdo;
        $query = "SELECT  subject_name, semester, teacher_name, teacher_id, program_id, id, subject_code FROM subject_with_program_id WHERE program_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}


function getTeacherWithSubject($teacher_name, $semester)
{
    global $pdo;
    $sql = "SELECT subject_code, subject_name, COUNT(student_id) AS student_count  FROM student_with_subjects WHERE teacher_id = ? AND semester = ? GROUP BY subject_code, subject_name";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_name, $semester]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}

function getSubject1($id)
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




function InsertNewStudent($student_id, $lname, $fname, $mname, $contact, $email, $program, $course, $sy)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_code = ? AND email = ? AND contact = ?");
        $stmt->execute([$student_id, $email, $contact]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: students.php?error=Student Already Exists");
            return false;
        } else {
            $query = "INSERT INTO students ( student_code, lname, fname, mname, contact, email, program, course ,school_year) 
                                       VALUES ( ?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($query);
            if ($stmt->execute([$student_id, $lname, $fname, $mname, $contact, $email, $program, $course, $sy])) {
                header("Location: students.php?success=Student Added Successfully");
                return true;
            } else {
                header("Location: students.php?error=Failed to Add Student");
                return false;
            }
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return false;
    }
}



function InsertNewTeacher($employee_id, $lname, $fname, $mname, $contact, $email, $profession, $specialization, $schoolyear)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_code = ? AND email = ? AND contact = ?");
        $stmt->execute([$employee_id, $email, $contact]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: teachers.php?error=Teachers Already Exists");
            return false;
        } else {
            $query = "INSERT INTO teachers ( teacher_code, lname, fname, mname, contact, email, profession, specialized , school_year) 
                                       VALUES ( ?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($query);
            if ($stmt->execute([$employee_id, $lname, $fname, $mname, $contact, $email, $profession, $specialization, $schoolyear])) {
                header("Location: teachers.php?success=Teacher Added Successfully");
                return true;
            } else {
                header("Location: teachers.php?error=Failed to Add teachers");
                return false;
            }
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return false;
    }
}

function DeleteProgramByID($minorid)
{
    global $pdo;

    try {
        $subject = "DELETE FROM subject_with_program_id WHERE program_id = ?";
        $stmt2 = $pdo->prepare($subject);
        $stmt2->execute([$minorid]);

        $query = "DELETE FROM programs_with_subjects WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$minorid]);
        return true;
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return false;
    }
}




function DeleteSectionByID($minorid)
{
    global $pdo;
    $query = "DELETE FROM sections WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$minorid])) {
        return true;
    } else {
        return false;
    }
}



function DeleteTeacherByID($student_code)
{
    global $pdo;

    try {
        $stmt3 = $pdo->prepare("DELETE FROM teachers WHERE teacher_code = ?");
        $stmt3->execute([$student_code]);

        header("Location: teachers.php?success=Teacher Deleted Successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: teachers.php?error=Failed to Delete Teacher");
        exit();
    }
}

function GetProgramWithId($id)
{
    global $pdo;
    try {
        $stmt3 = $pdo->prepare("Select * from programs_with_subjects WHERE id = ?");
        $stmt3->execute([$id]);
        $row = $stmt3->fetch();
        return $row;
    } catch (PDOException $e) {
        return false;
    }
}

function InsertNewSubject2($teacher_id, $semester_id, $subject_name, $subject_code, $id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM subject_with_program_id WHERE subject_name = ? AND subject_code = ? AND teacher_name = ?");
        $stmt->execute([$subject_name, $subject_code, $teacher_id]);
        $exists = $stmt->fetchColumn();
        if ($exists > 0) {
            header('Location: view_student.php?error=Duplicate Error, Please Use Unique Subject Name and Code&program_id=' . $id);
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO subject_with_program_id ( teacher_name, semester, subject_name, subject_code, program_id ) 
                                   VALUES ( ?,?,?,?,?)");
            $stmt->execute([
                $teacher_id,
                $semester_id,
                $subject_name,
                $subject_code,
                $id
            ]);

            header('Location: view_student.php?success=Subject added successfully&program_id=' . $id);
            return true;
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        header('Location: view_student.php?error=Failed to add subject&program_id=' . $id);
        return false;
    }
}

function getProgramById($program_id)
{
    global $pdo;
    try {
        $stmt3 = $pdo->prepare("Select * from programs_with_subjects WHERE id = ?");
        $stmt3->execute([$program_id]);
        $row = $stmt3->fetch();
        return $row;
    } catch (PDOException $e) {
        return false;
    }
}

function InsertNewSubjectByAdmin($teacher_name, $semester_id, $subject_name, $subject_code, $id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM subject_with_program_id WHERE semester=? AND program_id=? AND subject_name = ? AND subject_code = ? AND teacher_id = ?");
        $stmt->execute([$semester_id, $id, $subject_name, $subject_code, $teacher_name]);
        $exists = $stmt->fetchColumn();
        if ($exists > 0) {
            header('Location: view_program.php?error=Duplicate Error, Please Use Unique Subject Name and Code&program_id=' . $id);
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO subject_with_program_id ( teacher_id, semester, subject_name, subject_code, program_id ) 
                                   VALUES ( ?,?,?,?,?)");
            $stmt->execute([
                $teacher_name,
                $semester_id,
                $subject_name,
                $subject_code,
                $id
            ]);

            header('Location: view_program.php?success=Subject added successfully&program_id=' . $id);
            return true;
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        header('Location: view_program.php?error=Failed to add subject&program_id=' . $id);
        return false;
    }
}


/* function GetCourse()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM programs");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } catch (PDOException $e) {
        return false;
    }
} */


function InsertNewPrograms($program_course, $department_program, $schoolyear)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM programs_with_subjects WHERE program_course = ? AND department_program = ? AND school_year = ?");
        $stmt->execute([$program_course, $department_program, $schoolyear]);
        $exists = $stmt->fetchColumn();
        if ($exists > 0) {
            header('location: program.php?error=Duplicate Error, Please Use Unique Course, SY and Program');
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO programs_with_subjects (program_course, department_program, school_year ) VALUES (?, ?, ?)");
            if ($stmt->execute([$program_course, $department_program, $schoolyear])) {
                $_SESSION["program_course"] = $program_course;
                $_SESSION["department_program"] = $department_program;
                $_SESSION["school_year"] = $schoolyear;

                header('location: program.php?success=Programs added successfully');
                exit();
            } else {
                header('location: program.php?error=Programs added Unsuccessfully');
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        header('location: program.php?error=Programs added Unsuccessfully');
        exit();
    }
}

function GetProgramsById($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM programs_with_subjects WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: [];
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Logs the error
        return [];
    }
}

function updateSubject($subject_name, $subject_code, $teacher_name, $id)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE programs_with_subjects SET subject_name = ?, subject_code = ?, teacher_name = ? WHERE id = ?");

    try {
        if ($stmt->execute([$subject_name, $subject_code, $teacher_name, $id])) {
            header('location: program.php?success=Section updated successfully');
            exit();
        } else {
            header('location: program.php?error=Section updated Unsuccessfully');
            exit();
        }
    } catch (Throwable $th) {
        $_SESSION["error"] = "Failed to update section!";
    }
}
function InsertNewSubject($subjectName, $section, $program, $syear, $admin_id, $teacher_id, $subjectCode, $semester)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO subject_year (name, code, year_id, semester_id, program_id, teacher_id, section_id) VALUES
        (?,?,?,?,?,?,?)");

        if ($stmt->execute([$subjectName, $subjectCode, $syear, $semester, $program, $teacher_id, $section])) {
            $stmt = $pdo->prepare("INSERT INTO subjects (subject_name, subject_code, admin_id, program_id, semester_id, teacher_id, section_id) VALUES
            (?,?,?,?,?,?,?)");
            $stmt->execute([$subjectName, $subjectCode, $admin_id, $program, $semester, $teacher_id, $section]);


            header('location: subjects.php?success=Subject added successfully');
            return true;
        } else {
            header('location: subjects.php?error=Subject added Unsuccessfully');
            return false;
        }
    } catch (PDOException $th) {
        header('location: subjects.php?error=Subject added Unsuccessfully');
    }
}


function CheckTeacherCode($teacherId)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_code = ?");
        $stmt->execute([$teacherId]);
        return $stmt->fetch() ? true : false; // Just return true or false
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
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

function CheckExistingSubject($subjectCode, $subjectName)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE subject_code = ? OR subject_name = ?");
        $stmt->execute([$subjectCode, $subjectName]);

        $result = $stmt->fetch();
        if ($result) {
            header('location: program.php?error1=Duplicate Error, Please Use Unique Subject Name and Code');
            return true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    return false;
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