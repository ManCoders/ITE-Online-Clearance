<?php
// Start output buffering to prevent TCPDF errors
ob_start();

require_once('../tcpdf/tcpdf.php');
include "../db_connect.php";
include "./student_function.php";

session_start();
if (!isset($_SESSION['student_id'])) {
    header('location: ../index.php');
    exit();
}

$student_id = $_SESSION['student_id'];
$student = getMyId($student_id);
$subjects = getSubject2($student_id);

// Separate subjects by semester
$firstSemesterSubjects = [];
$secondSemesterSubjects = [];

foreach ($subjects as $row) {
    if ($row['semester'] == 1) {
        $firstSemesterSubjects[] = $row;
    } elseif ($row['semester'] == 2) {
        $secondSemesterSubjects[] = $row;
    }
}

// Create a new PDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('School System');
$pdf->SetTitle('Student Grades');
$pdf->SetSubject('Grades Report');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// --- HEADER ---
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetFillColor(128, 0, 0); // Dark Red Background
$pdf->SetTextColor(255, 255, 255); // White Text
$pdf->Cell(0, 15, 'STUDENT GRADES REPORT', 0, 1, 'C', true);

$pdf->Ln(5);

// --- STUDENT INFO ---
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$student_info = "
<table>
    <tr>
        <td><strong>Name:</strong> {$student['student_name']}</td>
        <td><strong>Email:</strong> {$student['email']}</td>
    </tr>
    <tr>
        <td><strong>Student ID:</strong> {$student['student_code']}</td>
        <td><strong>School Year:</strong> {$student['school_year']}</td>
    </tr>
    <tr>
        <td><strong>Course:</strong> {$student['program']} - {$student['course']}</td>
    </tr>
</table>
";
$pdf->writeHTML($student_info, true, false, true, false, '');

$pdf->Ln(5);

// Function to generate semester tables
function generateSemesterTable($pdf, $semesterTitle, $subjects)
{
    if (empty($subjects)) {
        return; // Skip if no subjects in semester
    }

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetFillColor(128, 0, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(0, 10, $semesterTitle, 0, 1, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(2);

    $html = '
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #800000; /* Dark Red */
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            font-size: 11px;
        }
        td {
            text-align: center;
            padding: 8px;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>

    <table border="1">
        <tr>
            <th>#</th>
            <th>College Level</th>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Grade</th>
            <th>Initial</th>
            <th>Instructor</th>
        </tr>';

    foreach ($subjects as $index => $row) {
        $teacher = getTeacherById($row['teacher_id'] ?? 0);

        $final = !empty($row['final']) ? $row['final'] : 'Not Provided'; // Default remark
        $teacher_name = !empty($teacher['teacher_name']) ? $teacher['teacher_name'] : 'Unknown'; // Default instructor
        $level = getCollegeLevelbyTeacher($row['college_level']);
        $html .= '<tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($level['year_level'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['subject_code'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['subject_name'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['grade'] ?? '') . '</td>
                    <td>' . htmlspecialchars($final) . '</td>
                    <td>' . htmlspecialchars($teacher_name) . '</td>
                  </tr>';
    }

    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(10);
}

// Generate tables for each semester
generateSemesterTable($pdf, "1ST SEMESTER", $firstSemesterSubjects);
generateSemesterTable($pdf, "2ND SEMESTER", $secondSemesterSubjects);

// --- FOOTER ---
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Generated on ' . date('F d, Y'), 0, 0, 'C');

// Clear any output before sending PDF
ob_end_clean();
$pdf->Output('student_grades.pdf', 'D');
