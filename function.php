<?php
function getTeacherInfo($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();

        if ($student) {
            return $student;
        } else {
            return ["status" => "error", "message" => "Student not found"];
        }
    } catch (PDOException $e) {
        return ["status" => "error", "message" => $e->getMessage()];
    }
}





function getAdminInfo($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();

        if ($student) {
            return $student;
        } else {
            return ["status" => "error", "message" => "Student not found"];
        }
    } catch (PDOException $e) {
        return ["status" => "error", "message" => $e->getMessage()];
    }
}

?>