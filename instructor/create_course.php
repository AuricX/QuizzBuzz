<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Create Course - QuizzBuzz';
$currentPage = 'create_course';

require_once __DIR__ . '/../db/conx.php';

// Fetch categories for the dropdown
$categories = [];
try {
    $stmt = $database->query('SELECT category_id, name FROM category ORDER BY name');
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $level = $_POST['level'] ?? 'Normal';
    $price = $_POST['price'] ?? 49.99;
    $category_id = $_POST['category_id'] ?? null;
    if ($title && $category_id) {
        try {
            $stmt = $database->prepare('INSERT INTO course (title, description, level, price, teacher_id, category_id) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $description, $level, $price, $_SESSION['user_id'], $category_id]);
            $message = '<div class="alert alert-success">Course created successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Title and category are required.</div>';
    }
}
// Start output buffering to capture content for the layout
ob_start();
?>
<div class="container mt-4">
    <h2 class="mb-4">Create a New Course</h2>
    <?php echo $message; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-select">
                <option value="Very Easy">Very Easy</option>
                <option value="Easy">Easy</option>
                <option value="Normal" selected>Normal</option>
                <option value="Hard">Hard</option>
                <option value="Very Hard">Very Hard</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Price ($)</label>
            <input type="number" name="price" class="form-control" min="0" step="0.01" value="49.99" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php'; 