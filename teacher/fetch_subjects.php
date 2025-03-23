<?php
session_start();
include "../db_connect.php";
include "./teacher_function.php";

// Ensure user is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$subjects = getSubjectTeacherDashboard($teacher_id, $search_query, $offset, $limit);
$totalSubjects = getTotalSubjects($teacher_id, $search_query);
$totalPages = ceil($totalSubjects / $limit);

$response = [
    "subjects" => $subjects,
    "totalPages" => $totalPages,
    "currentPage" => $page
];

echo json_encode($response);
?>