<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'My Courses - QuizzBuzz';
$currentPage = 'courses';

require_once __DIR__ . '/../db/conx.php';
require_once __DIR__ . '/../components/card.php';

$instructorId = $_SESSION['user_id'];
$message = '';

// Handle delete course
if (isset($_POST['delete_course_id'])) {
    $deleteCourseId = (int)$_POST['delete_course_id'];
    try {
        $database->beginTransaction();
        // Get all quizzes for this course
        $quizStmt = $database->prepare('SELECT quiz_id FROM quiz WHERE course_id = ?');
        $quizStmt->execute([$deleteCourseId]);
        $quizIds = $quizStmt->fetchAll(PDO::FETCH_COLUMN);
        if ($quizIds) {
            $qMarks = str_repeat('?,', count($quizIds) - 1) . '?';
            $questionStmt = $database->prepare("SELECT question_id FROM question WHERE quiz_id IN ($qMarks)");
            $questionStmt->execute($quizIds);
            $questionIds = $questionStmt->fetchAll(PDO::FETCH_COLUMN);
            if ($questionIds) {
                $inQ = str_repeat('?,', count($questionIds) - 1) . '?';
                $database->prepare("DELETE FROM choice WHERE question_id IN ($inQ)")->execute($questionIds);
                $database->prepare("DELETE FROM question WHERE question_id IN ($inQ)")->execute($questionIds);
            }
            $database->prepare("DELETE FROM quiz WHERE quiz_id IN ($qMarks)")->execute($quizIds);
        }
        $database->prepare('DELETE FROM enrollment WHERE course_id = ?')->execute([$deleteCourseId]);
        $database->prepare('DELETE FROM payment WHERE course_id = ?')->execute([$deleteCourseId]);
        $database->prepare('DELETE FROM course WHERE course_id = ? AND teacher_id = ?')->execute([$deleteCourseId, $instructorId]);
        $database->commit();
        $message = '<div class="alert alert-success">Course deleted successfully!</div>';
    } catch (PDOException $e) {
        $database->rollBack();
        $message = '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Fetch instructor's courses
try {
    $stmt = $database->prepare("
        SELECT c.course_id, c.title, c.description, c.level, c.price, cat.name AS category_name, COUNT(q.quiz_id) AS quiz_count
        FROM course c
        LEFT JOIN quiz q ON c.course_id = q.course_id
        JOIN category cat ON c.category_id = cat.category_id
        WHERE c.teacher_id = :teacher_id
        GROUP BY c.course_id
        ORDER BY c.course_id DESC
    ");
    $stmt->execute(['teacher_id' => $instructorId]);
} catch (PDOException $e) {
    $message = '<div class="alert alert-danger">Unable to load courses. Please try again later.</div>';
}

ob_start();
?>
<div class="container mt-4">
    <h2 class="mb-4">My Courses</h2>
    <?php echo $message; ?>
    <div class="row">
        <?php while ($course = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                        <div class="mb-2 text-muted">
                            <small>
                                <i class="bi bi-bar-chart"></i> Level: <?php echo htmlspecialchars($course['level']); ?><br>
                                <i class="bi bi-currency-dollar"></i> Price: $<?php echo number_format($course['price'], 2); ?><br>
                                <i class="bi bi-tags"></i> Category: <?php echo htmlspecialchars($course['category_name']); ?><br>
                                <i class="bi bi-question-circle"></i> Quizzes: <?php echo $course['quiz_count']; ?>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="/instructor/course?id=<?php echo $course['course_id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-gear"></i> Manage
                        </a>
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this course? This cannot be undone.');" style="margin:0;">
                            <input type="hidden" name="delete_course_id" value="<?php echo $course['course_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php'; 