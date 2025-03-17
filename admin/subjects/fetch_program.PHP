<?php
require 'database.php'; // Ensure DB connection

if (isset($_GET['id'])) {
    $programId = intval($_GET['id']);
    $program = GetProgramWithId($programId);

    if ($program) {
        $subjects = GetProgramWithId($programId); // Assume this function exists

        echo json_encode([
            "program_name" => $program['program_name'],
            "subjects" => $subjects
        ]);
    } else {
        echo json_encode(["error" => "Program not found"]);
    }
}
?>