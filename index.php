<?php
$pageTitle = 'Student Dashboard - QuizzBuzz';
$currentPage = 'home';

// Start output buffering to capture content for the layout
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">Recent Quizzes</h2>
        <div class="row">
            <?php
            // Example quizzes data (in real app, this would come from a database)
            $quizzes = [
                [
                    'title' => 'Introduction to PHP',
                    'content' => 'Test your knowledge of PHP basics with this comprehensive quiz.',
                    'image' => 'assets/img/php-quiz.jpg',
                    'actions' => [
                        ['url' => '/quiz/1', 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                        ['url' => '#details-1', 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                    ]
                ],
                [
                    'title' => 'JavaScript Fundamentals',
                    'content' => 'Challenge yourself with core JavaScript concepts and problems.',
                    'image' => 'assets/img/js-quiz.jpg',
                    'actions' => [
                        ['url' => '/quiz/2', 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                        ['url' => '#details-2', 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                    ]
                ]
            ];

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