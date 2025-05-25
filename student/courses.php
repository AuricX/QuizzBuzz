<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'My Courses - QuizzBuzz';
$currentPage = 'courses';

require_once __DIR__ . '/../db/conx.php';
require_once __DIR__ . '/../components/card.php';
// Start output buffering to capture content for the layout
ob_start();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">My Enrolled Courses</h2>
        <div class="row">
            <?php
            try {
                $stmt = $database->prepare("
                    SELECT c.course_id, c.title, c.description, c.level, t.name AS instructor_name, COUNT(q.quiz_id) AS quiz_count
                    FROM course c
                    JOIN teacher t ON c.teacher_id = t.teacher_id
                    LEFT JOIN quiz q ON c.course_id = q.course_id
                    JOIN enrollment e ON c.course_id = e.course_id
                    WHERE e.student_id = :student_id
                    GROUP BY c.course_id
                    ORDER BY c.course_id DESC
                ");
                
                $stmt->execute(['student_id' => $_SESSION['user_id']]);

                while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $actions = [
                        ['url' => '/student/course?id=' . $course['course_id'], 'text' => 'View Course', 'icon' => 'book', 'class' => 'btn-primary']
                    ];
                    $footer = '<div class="text-muted">'
                        . '<small>'
                        . '<i class="bi bi-person"></i> Instructor: ' . htmlspecialchars($course['instructor_name']) . '<br>'
                        . '<i class="bi bi-bar-chart"></i> Level: ' . htmlspecialchars($course['level']) . '<br>'
                        . '<i class="bi bi-question-circle"></i> Quizzes: ' . $course['quiz_count']
                        . '</small>'
                        . '</div>';
                    renderCard(
                        $course['title'],
                        $course['description'],
                        $footer,
                        null, // No image in schema
                        $actions
                    );
                }
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                echo '<div class="alert alert-danger">Unable to load courses. Please try again later.</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/../layouts/main.php';
?>