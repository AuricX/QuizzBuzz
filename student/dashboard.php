<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Student Dashboard - QuizzBuzz';
$currentPage = 'home';

require_once __DIR__ . '/../db/conx.php';

// Get the logged-in student's ID from the session
$student_id = $_SESSION['user_id'];

// Query to get course-wise quiz performance
$query = "
    SELECT 
        c.course_id,
        c.title as course_title,
        COUNT(DISTINCT q.quiz_id) as total_quizzes,
        COUNT(DISTINCT qa.quiz_id) as attempted_quizzes,
        COALESCE(AVG(CASE WHEN qa.score IS NOT NULL THEN qa.score END), 0) as average_score
    FROM course c
    LEFT JOIN quiz q ON c.course_id = q.course_id
    LEFT JOIN quiz_attempt qa ON q.quiz_id = qa.quiz_id AND qa.student_id = :student_id
    WHERE c.course_id IN (
        SELECT course_id 
        FROM enrollment 
        WHERE student_id = :student_id
    )
    GROUP BY c.course_id, c.title
    ORDER BY c.title";

$stmt = $database->prepare($query);
$stmt->execute(['student_id' => $student_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate overall statistics
$total_courses = count($courses);
$total_quizzes = 0;
$total_attempted = 0;
$total_score = 0;
$courses_with_attempts = 0;

foreach ($courses as $course) {
    $total_quizzes += $course['total_quizzes'];
    $total_attempted += $course['attempted_quizzes'];
    if ($course['attempted_quizzes'] > 0) {
        $total_score += $course['average_score'];
        $courses_with_attempts++;
    }
}

$overall_average = $courses_with_attempts > 0 ? $total_score / $courses_with_attempts : 0;
$completion_rate = $total_quizzes > 0 ? ($total_attempted / $total_quizzes) * 100 : 0;

// Start output buffering to capture content for the layout
ob_start();
?>

<!-- Overall Statistics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Overall Average</h5>
                <h2 class="display-4"><?php echo number_format($overall_average, 1); ?>%</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Quiz Completion Rate</h5>
                <h2 class="display-4"><?php echo number_format($completion_rate, 1); ?>%</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Courses</h5>
                <h2 class="display-4"><?php echo $total_courses; ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Course-wise Performance -->
<h2 class="mb-3">Course Performance</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Course</th>
                <th>Quizzes Completed</th>
                <th>Total Quizzes</th>
                <th>Average Score</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['course_title']); ?></td>
                <td><?php echo $course['attempted_quizzes']; ?></td>
                <td><?php echo $course['total_quizzes']; ?></td>
                <td>
                    <?php 
                    if ($course['attempted_quizzes'] > 0) {
                        echo number_format($course['average_score'], 1) . '%';
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>
                <td>
                    <div class="progress">
                        <?php 
                        $progress = $course['total_quizzes'] > 0 
                            ? ($course['attempted_quizzes'] / $course['total_quizzes']) * 100 
                            : 0;
                        ?>
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?php echo $progress; ?>%"
                             aria-valuenow="<?php echo $progress; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo number_format($progress, 1); ?>%
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>