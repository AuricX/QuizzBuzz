<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Instructor Dashboard - QuizzBuzz';
$currentPage = 'home';

require_once __DIR__ . '/../db/conx.php';

$teacher_id = $_SESSION['user_id'];

// Start output buffering to capture content for the layout
ob_start();

try {
    // Query: courses with student count and best student
    $stmt = $database->prepare('
        SELECT c.course_id, c.title, COUNT(e.student_id) AS student_count,
               s.name AS best_student, e2.final_grade AS best_grade
        FROM course c
        LEFT JOIN enrollment e ON c.course_id = e.course_id
        LEFT JOIN (
            SELECT e1.course_id, e1.student_id, e1.final_grade
            FROM enrollment e1
            WHERE e1.final_grade IS NOT NULL
            AND (e1.course_id, e1.final_grade) IN (
                SELECT course_id, MAX(final_grade) FROM enrollment WHERE final_grade IS NOT NULL GROUP BY course_id
            )
        ) e2 ON c.course_id = e2.course_id
        LEFT JOIN student s ON e2.student_id = s.student_id
        WHERE c.teacher_id = :teacher_id
        GROUP BY c.course_id
        ORDER BY student_count DESC, c.title ASC
    ');
    $stmt->execute(['teacher_id' => $teacher_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $courses = [];
    echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>
<div class="container mt-4">
    <h2 class="mb-4">Your Courses (by Enrollment)</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Enrolled Students</th>
                    <th>Best Student</th>
                    <th>Best Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo $course['student_count']; ?></td>
                    <td><?php echo $course['best_student'] ? htmlspecialchars($course['best_student']) : '<span class="text-muted">N/A</span>'; ?></td>
                    <td><?php echo $course['best_grade'] !== null ? number_format($course['best_grade'], 2) : '<span class="text-muted">N/A</span>'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>