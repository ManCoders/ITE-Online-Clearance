<?php
include('db_connection.php'); // Make sure to include your database connection

$semester = $_GET['semester'];
$query = $_GET['query'];


$sql = "SELECT * FROM subject_with_program_id WHERE semester = ? AND (subject_code LIKE ? OR subject_name LIKE ?)";
$stmt = $pdo->prepare($sql);
$searchTerm = "%" . $query . "%";
$stmt->execute([$semester, $searchTerm, $searchTerm]);

// Fetch the results and generate the HTML table rows
$subjects = $stmt->fetchAll();
$html = '';
foreach ($subjects as $index => $row) {
    $students = getStudentById($row['student_id']);
    $html .= '<tr>';
    $html .= '<td>' . ($index + 1) . '</td>';
    $html .= '<td>' . $row['subject_code'] . '</td>';
    $html .= '<td>' . $row['subject_name'] . '</td>';
    $html .= '<td>' . htmlspecialchars($students['student_name']) . '</td>';
    $html .= '<td>' . $row['status'] . '</td>';
    $html .= '<td>' . $row['remark'] . '</td>';
    $html .= '<td>' . $row['final'] . '</td>';
    $html .= '<td><a href="update_student.php?action=list&teacher_id=' . $_GET['teacher_id'] . '&student_id=' . $row['student_id'] . '&subject_id=' . $row['id'] . '"><i class="fa fa-eye"></i></a></td>';
    $html .= '</tr>';
}

// Return the generated HTML as a response
echo $html;
