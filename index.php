<?php
$pageTitle = 'Student Dashboard - QuizzBuzz';
$currentPage = 'home';

require_once __DIR__ . '/conx.php';
// Start output buffering to capture content for the layout
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">Recent Quizzes</h2>
        <div class="row">
            <?php
            // Fetch quizzes from the database
            try {
                $stmt = $database->query("SELECT id, title, content, image FROM quizzes ORDER BY created_at DESC");
                $quizzes = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $quizzes[] = [
                        'title' => $row['title'],
                        'content' => $row['content'],
                        'image' => $row['image'],
                        'actions' => [
                            ['url' => '/quiz/' . $row['id'], 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                            ['url' => '#details-' . $row['id'], 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                        ]
                    ];
                }
            } catch(PDOException $e) {
                // Log error and show user-friendly message
                error_log("Database Error: " . $e->getMessage());
                $quizzes = [];
                echo '<div class="alert alert-danger">Unable to load quizzes. Please try again later.</div>';
            }

            // Include the card component
            require_once __DIR__ . '/components/card.php';

            // Render quiz cards
            foreach ($quizzes as $quiz) {
                renderCard(
                    $quiz['title'],
                    $quiz['content'],
                    null,
                    $quiz['image'] ?? null,
                    $quiz['actions']
                );
            }
            ?>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Your Progress</h5>
                <div class="d-flex justify-content-between mb-3">
                    <span>Completed Quizzes:</span>
                    <strong>12</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Average Score:</span>
                    <strong>85%</strong>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 85%;" 
                         aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/layouts/main.php';
?>