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
$sql = "SELECT * FROM students WHERE 1=1";
$conditions = [];
$params = [];

// Name filters (lname, fname, mname etc)
if (!empty($student_name)) {
    $conditions[] = "(lname LIKE :name OR fname LIKE :name OR mname LIKE :name OR contact LIKE :name OR program LIKE :name OR course LIKE :name OR school_year LIKE :name OR levels LIKE :name OR email LIKE :name )";
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
                        <th>Student ID</th>
                        <th>Complete Name</th>
                        <th>Programs</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $student): ?>
                        <tr style="text-align: center;">
                            <td><?= $index + 1 ?>.</td>
                            <td style="text-align: left;"><?= htmlspecialchars($student['student_code']) ?></td>
                            <td style="text-align: left;">
                                <?= htmlspecialchars($student['lname']) . ', ' . htmlspecialchars($student['fname']) . ' ' . htmlspecialchars($student['mname']) ?>
                            </td>
                            <td><?= htmlspecialchars($student['program']) ?></td>
                            <td style="text-align: left;"><?= htmlspecialchars($student['course']) ?></td>
                            <td style="text-align: left;">
                                <?php
                                $year_level = getYearLevel($student['levels']);
                                foreach ($year_level as $year_level_value) {
                                    echo htmlspecialchars($year_level_value['year_level']);
                                }
                                ?>
                            </td>
                            <td style="text-align: left;"><?= htmlspecialchars($student['email']) ?></td>
                            <td style="text-align: left;"><?= htmlspecialchars($student['contact']) ?></td>
                            <td>
                                <div style="color: aliceblue;">
                                    <a
                                        href="view_student.php?program_id=<?= $student['id']; ?>&section_id=<?= urlencode($student['section_id']); ?>&program_name=<?= urlencode($student['program']); ?>&student_name=<?= urlencode($student['lname'] . ', ' . $student['fname'] . ' ' . $student['mname']); ?>&course_name=<?= urlencode($student['course']); ?>&student_id=<?= urlencode($student['student_code']); ?>">
                                        <i style="color: aliceblue;" class="fa fa-eye"></i>
                                    </a>
                                    <a href="students.php?delete_program=<?= $student['id']; ?>" class="delete-student-btn" data-id="<?= $student['id']; ?>"
                                        style="color: red;">
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