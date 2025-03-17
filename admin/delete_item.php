<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin_id'])) {
    header('location: ../index.php');
    exit();
}

if (isset($_GET['category'], $_GET['item'])) {
    $category = $_GET['category'];
    $item = $_GET['item'];

    // Table and column mapping
    $tableMap = [
        'programs' => ['table' => 'programs', 'column' => 'program_name'],
        'subjects' => ['table' => 'subjects', 'column' => 'subject_name'],
        'students' => ['table' => 'students', 'column' => 'lname'],
        'teachers' => ['table' => 'teachers', 'column' => 'lname']
    ];

    if (array_key_exists($category, $tableMap)) {
        $table = $tableMap[$category]['table'];
        $column = $tableMap[$category]['column'];

        $query = "DELETE FROM $table WHERE $column = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$item]);

        header("Location: index.php");
        exit();
    }
}
?>