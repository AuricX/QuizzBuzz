<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Take Quiz - QuizzBuzz';
$currentPage = 'courses';

require_once __DIR__ . '/../db/conx.php';

// Get quiz ID from URL
$quizId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$quizId) {
    header('Location: /student/courses');
    exit;
}

// Start output buffering to capture content for the layout
ob_start();

try {
    // First verify the student is enrolled in the course that contains this quiz
    $stmt = $database->prepare("
        SELECT 1 
        FROM quiz q
        JOIN course c ON q.course_id = c.course_id
        JOIN enrollment e ON c.course_id = e.course_id
        WHERE q.quiz_id = :quiz_id AND e.student_id = :student_id
    ");
    $stmt->execute([
        'quiz_id' => $quizId,
        'student_id' => $_SESSION['user_id']
    ]);
    
    if (!$stmt->fetch()) {
        throw new Exception('You are not enrolled in this course or the quiz does not exist');
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $answers = $_POST['answers'] ?? [];
        $correctAnswers = 0;
        $totalQuestions = 0;

        // Get all questions and their correct answers
        $stmt = $database->prepare("
            SELECT q.question_id, c.choice_id
            FROM question q
            JOIN choice c ON q.question_id = c.question_id
            WHERE q.quiz_id = :quiz_id AND c.is_correct = 1
        ");
        $stmt->execute(['quiz_id' => $quizId]);
        
        $correctChoices = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $correctChoices[$row['question_id']] = $row['choice_id'];
            $totalQuestions++;
        }

        // Calculate score
        foreach ($answers as $questionId => $choiceId) {
            if (isset($correctChoices[$questionId]) && $correctChoices[$questionId] == $choiceId) {
                $correctAnswers++;
            }
        }

        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

        // Record the attempt
        $stmt = $database->prepare("
            INSERT INTO quiz_attempt (student_id, quiz_id, score)
            VALUES (:student_id, :quiz_id, :score)
        ");
        $stmt->execute([
            'student_id' => $_SESSION['user_id'],
            'quiz_id' => $quizId,
            'score' => $score
        ]);

        // Show results
        echo '<div class="alert alert-success mb-4">';
        echo '<h4>Quiz Completed!</h4>';
        echo '<p>Your score: ' . number_format($score, 1) . '%</p>';
        echo '<p>Correct answers: ' . $correctAnswers . ' out of ' . $totalQuestions . '</p>';
        echo '</div>';
        
        // Add a button to go back to the course
        $stmt = $database->prepare("SELECT course_id FROM quiz WHERE quiz_id = ?");
        $stmt->execute([$quizId]);
        $courseId = $stmt->fetchColumn();
        
        echo '<a href="/student/course?id=' . $courseId . '" class="btn btn-primary">Back to Course</a>';
        
    } else {
        // Fetch quiz details
        $stmt = $database->prepare("
            SELECT q.title, c.title as course_title
            FROM quiz q
            JOIN course c ON q.course_id = c.course_id
            WHERE q.quiz_id = :quiz_id
        ");
        $stmt->execute(['quiz_id' => $quizId]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) {
            throw new Exception('Quiz not found');
        }
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($quiz['title']); ?></h2>
                <p class="text-muted">Course: <?php echo htmlspecialchars($quiz['course_title']); ?></p>
            </div>
        </div>

        <form method="post" class="quiz-form">
            <?php
            // Fetch questions and their choices
            $stmt = $database->prepare("
                SELECT q.question_id, q.body, c.choice_id, c.text
                FROM question q
                LEFT JOIN choice c ON q.question_id = c.question_id
                WHERE q.quiz_id = :quiz_id
                ORDER BY q.question_id, c.choice_id
            ");
            $stmt->execute(['quiz_id' => $quizId]);
            
            $currentQuestion = null;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($currentQuestion !== $row['question_id']) {
                    if ($currentQuestion !== null) {
                        echo '</div>'; // Close previous question's choices
                    }
                    $currentQuestion = $row['question_id'];
                    echo '<div class="card mb-4">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">Question ' . $currentQuestion . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars($row['body']) . '</p>';
                    echo '<div class="choices">';
                }
                
                echo '<div class="form-check mb-2">';
                echo '<input class="form-check-input" type="radio" name="answers[' . $row['question_id'] . ']" value="' . $row['choice_id'] . '" id="choice_' . $row['choice_id'] . '" required>';
                echo '<label class="form-check-label" for="choice_' . $row['choice_id'] . '">';
                echo htmlspecialchars($row['text']);
                echo '</label>';
                echo '</div>';
            }
            
            if ($currentQuestion !== null) {
                echo '</div>'; // Close last question's choices
                echo '</div>'; // Close card-body
                echo '</div>'; // Close card
            }
            ?>

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg">Submit Quiz</button>
            </div>
        </form>
    </div>
</div>

<?php
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/../layouts/main.php';
?> 