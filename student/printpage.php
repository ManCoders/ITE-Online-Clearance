<?php
ob_end_clean();
require('../assets/fpdf186/fpdf.php');
include "../db_connect.php";
include "./student_function.php";

if (!isset($_GET['student_id'])) {
    die('Invalid Student ID');
}

$student_id = $_GET['student_id'];
$student = getMyId($student_id);
if (!$student) {
    die('Invalid Student ID');
}

class PDF extends FPDF
{
    function Header()
    {
        // Header background
        $this->SetFillColor(130, 0, 0);
        $this->Rect(0, 0, 210, 40, 'F');

        // Title
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 20, 'STUDENT GRADES REPORT', 0, 1, 'C');
        $this->Ln(5);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTextColor(0, 0, 0);

// **Fixed Student Information Section**
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '', 0, 1, 'C'); // Centered Title
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 12);
$labelWidth = 45;
$valueWidth = 60;

// Arrange info in two columns
$data = [
    ['Name:', $student['student_name'], 'Email:', $student['email']],
    ['Student ID:', $student['student_code'], 'School Year:', $student['school_year']],
    ['Course:', $student['program'] . ' - ' . $student['course'], '', '']
];

foreach ($data as $row) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($labelWidth, 8, $row[0], 0, 0, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell($valueWidth, 8, $row[1], 0, 0, 'L');

    if (!empty($row[2])) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($labelWidth, 8, $row[2], 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell($valueWidth, 8, $row[3], 0, 1, 'L');
    } else {
        $pdf->Cell($labelWidth + $valueWidth, 8, '', 0, 1, 'L'); // Empty space
    }
}

$pdf->Ln(5);

// Function to display semester-wise grades
function displaySemester($pdf, $student_id, $semesterName, $semesterNum)
{
    $subject = getSubject2($student_id);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(130, 0, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(180, 8, strtoupper($semesterName), 1, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(150, 0, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(10, 8, '#', 1, 0, 'C', true);
    $pdf->Cell(35, 8, 'Course Code', 1, 0, 'C', true);
    $pdf->Cell(50, 8, 'Course Title', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'Grade', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Remark', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Initial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'Instructor', 1, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetFont('Arial', '', 10);
    $rowIndex = 1;
    foreach ($subject as $row) {
        if ($row['semester'] == $semesterNum) {
            $pdf->Cell(10, 8, $rowIndex++, 1, 0, 'C');
            $pdf->Cell(35, 8, $row['subject_code'], 1, 0, 'C');
            $pdf->Cell(50, 8, $row['subject_name'], 1, 0, 'C');
            $pdf->Cell(15, 8, $row['grade'], 1, 0, 'C');
            $pdf->Cell(30, 8, $row['remark'], 1, 0, 'C');
            $pdf->Cell(30, 8, $row['final'], 1, 0, 'C');



            $teacher = getTeacherById($row['teacher_id']);
            $pdf->Cell(20, 8, $teacher['teacher_name'], 1, 1, 'C');
        }
    }
    $pdf->Ln(5);
}

// Generate 1st and 2nd semester tables
displaySemester($pdf, $student_id, "1st Semester", 1);
displaySemester($pdf, $student_id, "2nd Semester", 2);

// Footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 0, 'Generated on ' . date('F d, Y'), 0, 0, 'C');

$pdf->Output();
