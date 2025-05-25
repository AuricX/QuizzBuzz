<?php
$pageTitle = 'Course Details - QuizzBuzz';
$currentPage = 'courses';

require_once __DIR__ . '/../db/conx.php';

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$courseId) {
    header('Location: /courses');
    exit;
}

// Start output buffering to capture content for the layout
ob_start();

try {
    // Fetch course details
    $stmt = $database->prepare("SELECT c.*, t.name as teacher_name, cat.name as category_name 
                               FROM course c 
                               JOIN teacher t ON c.teacher_id = t.teacher_id 
                               JOIN category cat ON c.category_id = cat.category_id 
                               WHERE c.course_id = ?");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        throw new Exception('Course not found');
    }
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <?php if ($course['image']): ?>
                <img src="<?php echo htmlspecialchars($course['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
            <?php endif; ?>
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h2>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                <div class="text-muted mb-4">
                    <i class="bi bi-person"></i> Instructor: <?php echo htmlspecialchars($course['teacher_name']); ?><br>
                    <i class="bi bi-tag"></i> Category: <?php echo htmlspecialchars($course['category_name']); ?><br>
                    <i class="bi bi-bar-chart"></i> Level: <?php echo htmlspecialchars($course['level']); ?>
                </div>
            </div>
        </div>

        <h3 class="mb-4">Course Quizzes</h3>
        <div class="row">
            <?php
            // Fetch quizzes for this course
            $stmt = $database->prepare("SELECT q.*, 
                                      (SELECT COUNT(*) FROM question WHERE quiz_id = q.quiz_id) as question_count 
                                      FROM quiz q 
                                      WHERE q.course_id = ? 
                                      ORDER BY q.quiz_id DESC");
            $stmt->execute([$courseId]);
            
            while ($quiz = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $actions = [
                    ['url' => '/quiz/' . $quiz['id'], 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                    ['url' => '#details-' . $quiz['id'], 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                ];
                
                renderCard(
                    $quiz['title'],
                    $quiz['content'],
                    null,
                    $quiz['image'],
                    $actions
                );
            }
            ?>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Course Progress</h5>
                <div class="d-flex justify-content-between mb-3">
                    <span>Completed Quizzes:</span>
                    <strong>0/<?php echo $stmt->rowCount(); ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Average Score:</span>
                    <strong>0%</strong>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" 
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo '<div class="alert alert-danger">Unable to load course details. Please try again later.</div>';
}

// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/layouts/main.php';
?> 