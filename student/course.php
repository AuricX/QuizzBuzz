<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Course Details - QuizzBuzz';
$currentPage = 'courses';

require_once __DIR__ . '/../db/conx.php';
require_once __DIR__ . '/../components/card.php';

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$courseId) {
    header('Location: /student/courses');
    exit;
}

// Start output buffering to capture content for the layout
ob_start();

try {
    // First check if student is enrolled in this course
    $stmt = $database->prepare("
        SELECT 1 
        FROM enrollment 
        WHERE student_id = :student_id AND course_id = :course_id
    ");
    $stmt->execute([
        'student_id' => $_SESSION['user_id'],
        'course_id' => $courseId
    ]);
    
    if (!$stmt->fetch()) {
        throw new Exception('You are not enrolled in this course');
    }

    // Fetch course details
    $stmt = $database->prepare("
        SELECT c.*, t.name as teacher_name, cat.name as category_name 
        FROM course c 
        JOIN teacher t ON c.teacher_id = t.teacher_id 
        JOIN category cat ON c.category_id = cat.category_id 
        WHERE c.course_id = :course_id
    ");
    $stmt->execute(['course_id' => $courseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        throw new Exception('Course not found');
    }
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h2>
                <div class="text-muted mb-3">
                    <i class="bi bi-person"></i> Instructor: <?php echo htmlspecialchars($course['teacher_name']); ?><br>
                    <i class="bi bi-bar-chart"></i> Level: <?php echo htmlspecialchars($course['level']); ?>
                </div>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            </div>
        </div>

        <h3 class="mb-4">Course Quizzes</h3>
        <div class="row">
            <?php
            // Fetch quizzes with attempt information
            $stmt = $database->prepare("
                SELECT 
                    q.quiz_id,
                    q.title,
                    COUNT(DISTINCT qa.attempt_id) as attempt_count,
                    MAX(qa.score) as best_score,
                    COUNT(DISTINCT qu.question_id) as question_count
                FROM quiz q
                LEFT JOIN quiz_attempt qa ON q.quiz_id = qa.quiz_id AND qa.student_id = :student_id
                LEFT JOIN question qu ON q.quiz_id = qu.quiz_id
                WHERE q.course_id = :course_id
                GROUP BY q.quiz_id, q.title
                ORDER BY q.quiz_id DESC
            ");
            
            $stmt->execute([
                'student_id' => $_SESSION['user_id'],
                'course_id' => $courseId
            ]);
            
            while ($quiz = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $attemptInfo = '';
                if ($quiz['attempt_count'] > 0) {
                    $attemptInfo = sprintf(
                        '<div class="text-muted"><small>Attempts: %d | Best Score: %.1f%%</small></div>',
                        $quiz['attempt_count'],
                        $quiz['best_score']
                    );
                }
                
                $actions = [
                    ['url' => '/student/quiz?id=' . $quiz['quiz_id'], 'text' => 'Take Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary']
                ];
                
                renderCard(
                    $quiz['title'],
                    sprintf('This quiz contains %d questions.', $quiz['question_count']),
                    $attemptInfo,
                    null,
                    $actions
                );
            }
            ?>
        </div>
    </div>
</div>

<?php
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/../layouts/main.php';
?> 