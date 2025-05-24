<?php
$pageTitle = 'Courses - QuizzBuzz';
$currentPage = 'courses';

require_once __DIR__ . '/conx.php';
// Start output buffering to capture content for the layout
ob_start();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Available Courses</h2>
        <div class="row">
            <?php
            try {
                $stmt = $database->query("
                    SELECT c.*, 
                           COUNT(q.id) as quiz_count,
                           GROUP_CONCAT(q.id) as quiz_ids
                    FROM courses c
                    LEFT JOIN quizzes q ON c.id = q.course_id
                    GROUP BY c.id
                    ORDER BY c.created_at DESC
                ");
                
                while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $actions = [
                        ['url' => '/course/' . $course['id'], 'text' => 'View Course', 'icon' => 'book', 'class' => 'btn-primary']
                    ];
                    $footer = '<div class="text-muted">'
                        . '<small>'
                        . '<i class="bi bi-person"></i> Instructor: ' . htmlspecialchars($course['instructor_name']) . '<br>'
                        . '<i class="bi bi-question-circle"></i> Quizzes: ' . $course['quiz_count']
                        . '</small>'
                        . '</div>';
                    renderCard(
                        $course['title'],
                        $course['description'],
                        $footer,
                        $course['image'],
                        $actions
                    );
                }
            } catch(PDOException $e) {
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
require_once __DIR__ . '/layouts/main.php';
?> 