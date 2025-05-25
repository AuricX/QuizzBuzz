<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../db/conx.php';

$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$courseId) {
    header('Location: /instructor/courses');
    exit;
}

// Fetch course details
$stmt = $database->prepare('SELECT * FROM course WHERE course_id = ? AND teacher_id = ?');
$stmt->execute([$courseId, $_SESSION['user_id']]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) {
    echo '<div class="alert alert-danger">Course not found or you do not have permission to manage it.</div>';
    exit;
}

// Handle add quiz with questions and choices
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quiz_modal'])) {
    $quizTitle = trim($_POST['modal_quiz_title'] ?? '');
    $questions = $_POST['questions'] ?? [];
    if ($quizTitle && !empty($questions)) {
        try {
            $database->beginTransaction();
            $stmt = $database->prepare('INSERT INTO quiz (course_id, title) VALUES (?, ?)');
            $stmt->execute([$courseId, $quizTitle]);
            $quizId = $database->lastInsertId();
            foreach ($questions as $q) {
                $qBody = trim($q['body'] ?? '');
                $choices = $q['choices'] ?? [];
                $correct = $q['correct'] ?? -1;
                if ($qBody && count($choices) >= 2 && isset($choices[$correct])) {
                    $stmt = $database->prepare('INSERT INTO question (quiz_id, body) VALUES (?, ?)');
                    $stmt->execute([$quizId, $qBody]);
                    $questionId = $database->lastInsertId();
                    foreach ($choices as $i => $choiceText) {
                        $stmt = $database->prepare('INSERT INTO choice (question_id, text, is_correct) VALUES (?, ?, ?)');
                        $stmt->execute([$questionId, $choiceText, $i == $correct ? 1 : 0]);
                    }
                }
            }
            $database->commit();
            $quizMsg = '<div class="alert alert-success">Quiz and questions added successfully!</div>';
        } catch (PDOException $e) {
            $database->rollBack();
            $quizMsg = '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $quizMsg = '<div class="alert alert-danger">Quiz title and at least one question are required.</div>';
    }
}
// Handle remove quiz
if (isset($_POST['remove_quiz_id'])) {
    $removeQuizId = (int)$_POST['remove_quiz_id'];
    try {
        // Delete choices for all questions in the quiz
        $qStmt = $database->prepare('SELECT question_id FROM question WHERE quiz_id = ?');
        $qStmt->execute([$removeQuizId]);
        $questionIds = $qStmt->fetchAll(PDO::FETCH_COLUMN);
        if ($questionIds) {
            $in = str_repeat('?,', count($questionIds) - 1) . '?';
            $database->prepare("DELETE FROM choice WHERE question_id IN ($in)")->execute($questionIds);
            $database->prepare("DELETE FROM question WHERE question_id IN ($in)")->execute($questionIds);
        }
        // Delete the quiz
        $stmt = $database->prepare('DELETE FROM quiz WHERE quiz_id = ? AND course_id = ?');
        $stmt->execute([$removeQuizId, $courseId]);
        $quizMsg = '<div class="alert alert-success">Quiz removed successfully!</div>';
    } catch (PDOException $e) {
        $quizMsg = '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
// Fetch quizzes
$stmt = $database->prepare('SELECT * FROM quiz WHERE course_id = ? ORDER BY quiz_id DESC');
$stmt->execute([$courseId]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories for modal
$categories = [];
try {
    $catStmt = $database->query('SELECT category_id, name FROM category ORDER BY name');
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Handle course update
$updateMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
    $newTitle = trim($_POST['title'] ?? '');
    $newDescription = trim($_POST['description'] ?? '');
    $newLevel = $_POST['level'] ?? 'Normal';
    $newPrice = $_POST['price'] ?? 49.99;
    $newCategory = $_POST['category_id'] ?? $course['category_id'];
    if ($newTitle && $newCategory) {
        try {
            $stmt = $database->prepare('UPDATE course SET title = ?, description = ?, level = ?, price = ?, category_id = ? WHERE course_id = ? AND teacher_id = ?');
            $stmt->execute([$newTitle, $newDescription, $newLevel, $newPrice, $newCategory, $courseId, $_SESSION['user_id']]);
            $updateMsg = '<div class="alert alert-success">Course updated successfully!</div>';
            // Refresh course details
            $stmt = $database->prepare('SELECT * FROM course WHERE course_id = ? AND teacher_id = ?');
            $stmt->execute([$courseId, $_SESSION['user_id']]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $updateMsg = '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $updateMsg = '<div class="alert alert-danger">Title and category are required.</div>';
    }
}

// Handle delete course
if (isset($_POST['delete_course'])) {
    try {
        $database->beginTransaction();
        // Get all quizzes for this course
        $quizStmt = $database->prepare('SELECT quiz_id FROM quiz WHERE course_id = ?');
        $quizStmt->execute([$courseId]);
        $quizIds = $quizStmt->fetchAll(PDO::FETCH_COLUMN);
        if ($quizIds) {
            // Get all questions for these quizzes
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
        // Delete enrollments and payments
        $database->prepare('DELETE FROM enrollment WHERE course_id = ?')->execute([$courseId]);
        $database->prepare('DELETE FROM payment WHERE course_id = ?')->execute([$courseId]);
        // Delete the course
        $database->prepare('DELETE FROM course WHERE course_id = ? AND teacher_id = ?')->execute([$courseId, $_SESSION['user_id']]);
        $database->commit();
        header('Location: /instructor/courses?deleted=1');
        exit;
    } catch (PDOException $e) {
        $database->rollBack();
        $updateMsg = '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Prepare quiz details for JS
$quizDetails = [];
foreach ($quizzes as $quiz) {
    $qStmt = $database->prepare('SELECT * FROM question WHERE quiz_id = ?');
    $qStmt->execute([$quiz['quiz_id']]);
    $questions = $qStmt->fetchAll(PDO::FETCH_ASSOC);
    $quizDetails[$quiz['quiz_id']] = [];
    foreach ($questions as $question) {
        $cStmt = $database->prepare('SELECT * FROM choice WHERE question_id = ?');
        $cStmt->execute([$question['question_id']]);
        $choices = $cStmt->fetchAll(PDO::FETCH_ASSOC);
        $quizDetails[$quiz['quiz_id']][] = [
            'body' => $question['body'],
            'choices' => $choices
        ];
    }
}

$pageTitle = 'Manage Course - QuizzBuzz';
$currentPage = 'courses';
ob_start();
?>
<div class="container mt-4">
    <h2><?php echo htmlspecialchars($course['title']); ?></h2>
    <?php echo $updateMsg; ?>
    <div class="mb-3">
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#manageCourseModal">
            <i class="bi bi-gear"></i> Manage Course
        </button>
    </div>
    <div class="mb-4">
        <strong>Description:</strong> <?php echo nl2br(htmlspecialchars($course['description'])); ?><br>
        <strong>Level:</strong> <?php echo htmlspecialchars($course['level']); ?><br>
        <strong>Price:</strong> $<?php echo number_format($course['price'], 2); ?><br>
        <strong>Category:</strong> <?php echo htmlspecialchars($course['category_id']); ?>
    </div>
    <!-- Manage Course Modal -->
    <div class="modal fade" id="manageCourseModal" tabindex="-1" aria-labelledby="manageCourseModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" action="">
            <input type="hidden" name="update_course" value="1">
            <div class="modal-header">
              <h5 class="modal-title" id="manageCourseModalLabel">Edit Course</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($course['description']); ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Level</label>
                <select name="level" class="form-select">
                  <?php foreach (["Very Easy", "Easy", "Normal", "Hard", "Very Hard"] as $level): ?>
                    <option value="<?php echo $level; ?>" <?php if ($course['level'] === $level) echo 'selected'; ?>><?php echo $level; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-control" min="0" step="0.01" value="<?php echo htmlspecialchars($course['price']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['category_id']; ?>" <?php if ($course['category_id'] == $cat['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
          <form method="post" onsubmit="return confirm('Are you sure you want to delete this course? This cannot be undone.');">
            <input type="hidden" name="delete_course" value="1">
            <button type="submit" class="btn btn-danger w-100 mt-2">Delete Course</button>
          </form>
        </div>
      </div>
    </div>
    <hr>
    <h4>Quizzes</h4>
    <?php echo $quizMsg; ?>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addQuizModal">
        <i class="bi bi-plus-circle"></i> Add Quiz
    </button>
    <!-- Add Quiz Modal -->
    <div class="modal fade" id="addQuizModal" tabindex="-1" aria-labelledby="addQuizModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form method="post" id="addQuizForm">
            <input type="hidden" name="add_quiz_modal" value="1">
            <div class="modal-header">
              <h5 class="modal-title" id="addQuizModalLabel">Add Quiz</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Quiz Title</label>
                <input type="text" name="modal_quiz_title" class="form-control" required>
              </div>
              <div id="questionsContainer"></div>
              <button type="button" class="btn btn-outline-primary mt-2" id="addQuestionBtn">Add Question</button>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create Quiz</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
    let questionCount = 0;
    document.getElementById('addQuestionBtn').addEventListener('click', function() {
        const qIdx = questionCount++;
        const qDiv = document.createElement('div');
        qDiv.className = 'card mb-3';
        qDiv.innerHTML = `
          <div class="card-body">
            <div class="mb-2">
              <label class="form-label">Question</label>
              <input type="text" name="questions[ ${qIdx}][body]" class="form-control" required>
            </div>
            <div class="choices-container"></div>
            <button type="button" class="btn btn-outline-secondary btn-sm addChoiceBtn">Add Choice</button>
            <input type="hidden" name="questions[ ${qIdx}][correct]" value="0">
          </div>
        `;
        document.getElementById('questionsContainer').appendChild(qDiv);
        const choicesContainer = qDiv.querySelector('.choices-container');
        let choiceCount = 0;
        function addChoice() {
            const cIdx = choiceCount++;
            const choiceDiv = document.createElement('div');
            choiceDiv.className = 'input-group mb-2';
            choiceDiv.innerHTML = `
              <div class="input-group-text">
                <input type="radio" name="questions[ ${qIdx}][correct]" value="${cIdx}" ${cIdx === 0 ? 'checked' : ''}>
              </div>
              <input type="text" name="questions[ ${qIdx}][choices][${cIdx}]" class="form-control" required placeholder="Choice">
            `;
            choicesContainer.appendChild(choiceDiv);
        }
        // Add two choices by default
        addChoice();
        addChoice();
        qDiv.querySelector('.addChoiceBtn').addEventListener('click', addChoice);
    });
    </script>
    <ul class="list-group">
        <?php foreach ($quizzes as $quiz): ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <strong><?php echo htmlspecialchars($quiz['title']); ?></strong>
                    <div>
                        <button type="button" class="btn btn-info btn-sm view-quiz-details" data-quiz-id="<?php echo $quiz['quiz_id']; ?>">View Details</button>
                        <form method="post" style="display:inline;margin:0;">
                            <input type="hidden" name="remove_quiz_id" value="<?php echo $quiz['quiz_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <!-- Quiz Details Modal -->
    <div class="modal fade" id="quizDetailsModal" tabindex="-1" aria-labelledby="quizDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="quizDetailsModalLabel">Quiz Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="quizDetailsBody">
            <!-- Populated by JS -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <script>
    const quizDetails = <?php echo json_encode($quizDetails); ?>;
    document.querySelectorAll('.view-quiz-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const quizId = this.getAttribute('data-quiz-id');
            const details = quizDetails[quizId];
            let html = '';
            if (details && details.length > 0) {
                details.forEach((q, idx) => {
                    html += `<div class='mb-3'><div class='fw-bold'>Q${idx+1}: ${q.body}</div><ul class='list-group list-group-flush'>`;
                    q.choices.forEach(choice => {
                        const correct = choice.is_correct ? 'list-group-item-success fw-bold' : '';
                        html += `<li class='list-group-item ${correct}'>${choice.text}`;
                        if (choice.is_correct) html += ' <span class="badge bg-success">Correct</span>';
                        html += '</li>';
                    });
                    html += '</ul></div>';
                });
            } else {
                html = '<div class="text-muted">No questions in this quiz.</div>';
            }
            document.getElementById('quizDetailsBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('quizDetailsModal')).show();
        });
    });
    </script>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php'; 