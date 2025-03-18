<?php
include "../db_connect.php";
include "../admin_function.php";

if (isset($_GET['program_id'])) {
    $programId = intval($_GET['program_id']);
    $program = GetProgramWithId($programId);

    if ($program) {
        echo json_encode([
            "program_course" => $program['program_course'],
            "department_program" => $program['department_program']
        ]);
    } else {
        echo json_encode(["error" => "Program not found"]);
    }
}
?>