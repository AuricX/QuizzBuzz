<?php
$pageTitle = 'Student Dashboard - QuizzBuzz';
$currentPage = 'home';

// Start output buffering to capture content for the layout
ob_start();

// include 'conx.php';
?>

<div class="container">
    <h1 class="mb-4">Recent Quizzes</h1>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
        <?php
            // Example quizzes data (in real app, this would come from a database)
            $quizzes = [
                [
                    'title' => 'Introduction to PHP',
                    'footer' => 'Only 1 day(s) remaining to complete the quiz! Hurry up!! ',
                    'content' => 'Test your knowledge of PHP basics with this comprehensive quiz.',
                    'image' => '/assets/img/js-quiz.png',
                    'actions' => [
                        ['url' => '/quiz/1', 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                        ['url' => '#details-1', 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                    ]
                ],
                [
                    'title' => 'Introduction to PHP',
                    'footer' => 'Only 1 day(s) remaining to complete the quiz! Hurry up!! ',
                    'content' => 'Test your knowledge of PHP basics with this comprehensive quiz.',
                    'image' => '/assets/img/js-quiz.png',
                    'actions' => [
                        ['url' => '/quiz/1', 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                        ['url' => '#details-1', 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                    ]
                ],
                [
                    'title' => 'Introduction to PHP',
                    'footer' => 'Only 1 day(s) remaining to complete the quiz! Hurry up!! ',
                    'content' => 'Test your knowledge of PHP basics with this comprehensive quiz.',
                    'image' => '/assets/img/js-quiz.png',
                    'actions' => [
                        ['url' => '/quiz/1', 'text' => 'Start Quiz', 'icon' => 'play-circle', 'class' => 'btn-primary'],
                        ['url' => '#details-1', 'text' => 'Details', 'icon' => 'info-circle', 'class' => 'btn-outline-secondary']
                    ]
                ],
                [
                    'title' => 'JavaScript Fundamentals',
                    'footer' => 'This is a footer',
                    'content' => 'Challenge yourself with core JavaScript concepts and problems.',
                    'image' => '/assets/img/js-quiz.png',
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
                    $quiz['footer'],
                    $quiz['image'] ?? null,
                    $quiz['actions']
                );
            }
        ?>
    </div>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/layouts/main.php';
?>