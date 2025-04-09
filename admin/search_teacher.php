<?php
session_start();
include "../db_connect.php"; // Ensure this sets up a $pdo instance
include "./admin_function.php";

// Get input values
$student_name = $_GET['student_name'] ?? '';
$contact = $_GET['contact'] ?? '';
$program = $_GET['program'] ?? '';
$course = $_GET['course'] ?? '';

// Start SQL
$sql = "SELECT * FROM teachers WHERE 1=1";
$conditions = [];
$params = [];

// Name filters (lname, fname, mname etc)
if (!empty($student_name)) {
    $conditions[] = "(lname LIKE :name OR fname LIKE :name OR mname LIKE :name OR contact LIKE :name OR email LIKE :name OR teacher_code LIKE :name )";
    $params[':name'] = "%$student_name%";
}


if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($results) > 0): ?>
    <div class="card-table" id="tableNone"
        style="width: 100%; color: #ccc; border: 1px solid #ccc; height: auto; background-color: #8B0000; overflow-y: scroll;">
        <div id="resulttable" class="tables-content" style="background-color: #8B0000; display: flex; color: white;">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No :</th>
                        <th>Teacher ID</th>
                        <th>Complete Name</th>
                        <th>Profession</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $student): ?>
                        <tr style="text-align: center;">
                            <td style="text-align: center;" ><?= $index + 1 ?>.</td>
                            <td style="text-align: center;"><?= htmlspecialchars($student['teacher_code']) ?></td>
                            <td style="text-align: left;">
                                <?= htmlspecialchars($student['lname']) . ', ' . htmlspecialchars($student['fname']) . ' ' . htmlspecialchars($student['mname']) ?>
                            </td>
                            <td><?= htmlspecialchars($student['profession']) ?></td>
                            <td style="text-align: left;"><?= htmlspecialchars($student['email']) ?></td>
                            
                            <td style="text-align: left;"><?= htmlspecialchars($student['contact']) ?></td>
                            <td>
                                <div style="color: aliceblue;">
                                    <a
                                        href="students_view.php?teacher_id=<?= $student['id']; ?>&section_id=<?= urlencode($student['section_id']); ?>&profession=<?= urlencode($student['profession']); ?>&teacher_name=<?= urlencode($student['lname'] . ', ' . $student['fname'] . ' ' . $student['mname']); ?>&employee_id=<?= urlencode($student['teacher_code']); ?>">
                                        <i style="color: aliceblue;" class="fa fa-eye"></i>
                                    </a>
                                    <a href="students_view.php?delete_program=<?= $student['id']; ?>" class="delete-student-btn" data-id="<?= $student['id']; ?>"
                                        style="color: white; cursor: pointer;">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <p style="color:white;">No students found.</p>
<?php endif; ?>