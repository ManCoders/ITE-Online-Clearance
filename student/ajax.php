<?php
include "../db_connect.php";

if (!isset($pdo)) {
    echo "<option value=''>Database connection failed</option>";
    exit;
}

if (isset($_POST['action'])) {

    if ($_POST['action'] == 'getPrograms' && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];

        if (!is_numeric($student_id)) {
            echo "<option value=''>Invalid Student ID</option>";
            exit;
        }

        $stmt = $pdo->prepare("SELECT DISTINCT program, course FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($programs) {
            foreach ($programs as $program) {
                echo "<option selected value='{$program['program']}'>{$program['program']} - {$program['course']}</option>";
            }
        } else {
            echo "<option value=''>No programs found</option>";
        }
    }

    if ($_POST['action'] == 'getSemester' && isset($_POST['semester'])) {
        $semester = $_POST['semester'];

        if (!is_numeric($semester)) {
            echo "<option value=''>Invalid Semester ID</option>";
            exit;
        }

        $stmt = $pdo->prepare('SELECT DISTINCT subject_code FROM subject_with_program_id WHERE semester = ?');
        $stmt->execute([$semester]);
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($subjects) {
            foreach ($subjects as $subject) {
                echo "<option value='{$subject['subject_code']}'>{$subject['subject_code']}</option>";
            }
        } else {
            echo "<option value=''>No subjects found</option>";
        }
    }

    if ($_POST['action'] == 'getSubjectCode' && isset($_POST['subjectCode'])) {
        $subjectCode = $_POST['subjectCode']; // Fixed incorrect variable assignment

        if (empty($subjectCode)) {
            echo "<option value=''>Invalid Subject Code</option>";
            exit;
        }

        $stmt = $pdo->prepare('SELECT DISTINCT subject_name FROM subject_with_program_id WHERE subject_code = ?');
        $stmt->execute([$subjectCode]);
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($subjects) {
            foreach ($subjects as $subject) {
                echo "<option value='{$subject['subject_name']}'>{$subject['subject_name']}</option>";
            }
        } else {
            echo "<option value=''>No subjects found</option>";
        }
    }

    if ($_POST['action'] == 'getTeacherSubjects' && (isset($_POST['subject_name']) || isset($_POST['subject_code']))) {
        $subject_name = $_POST['subject_name'] ?? ''; // Use default empty string if not set
        $subject_code = $_POST['subject_code'] ?? '';

        if (empty($subject_name) && empty($subject_code)) {
            echo "<option value=''>Invalid Subject Name or Code</option>";
            exit;
        }

        // Prepare SQL query with both `subject_name` and `subject_code`
        $stmt = $pdo->prepare('SELECT DISTINCT teacher_name FROM subject_with_program_id WHERE subject_name = ? AND subject_code = ?');
        $stmt->execute([$subject_name, $subject_code]);
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($teachers) {
            foreach ($teachers as $teacher) {
                echo "<option selected value='{$teacher['teacher_name']}'>{$teacher['teacher_name']}</option>";
            }
        } else {
            echo "<option value=''>No teachers found for this subject</option>";
        }
    }

}
?>