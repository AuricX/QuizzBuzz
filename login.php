<?php
// session_start(); // Removed because router.php already starts the session
require_once __DIR__ . '/db/conx.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    // Already logged in, redirect to correct dashboard
    if ($_SESSION['role'] === 'student') {
        header('Location: /student/dashboard');
    } else {
        header('Location: /instructor/dashboard');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($role === 'student') {
        $stmt = $database->prepare("SELECT * FROM student WHERE email = ?");
    } elseif ($role === 'instructor') {
        $stmt = $database->prepare("SELECT * FROM teacher WHERE email = ?");
    } else {
        $stmt = null;
    }

    if ($stmt) {
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Plaintext password check for local/testing
        if ($user && $password === $user['password_hash']) {
            $_SESSION['user_id'] = $user[$role === 'student' ? 'student_id' : 'teacher_id'];
            $_SESSION['role'] = $role;
            if ($role === 'student') {
                header('Location: /student/dashboard');
            } else {
                header('Location: /instructor/dashboard');
            }
            exit;
        } else {
            $error = 'Invalid credentials.';
        }
    } else {
        $error = 'Invalid role selected.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - QuizzBuzz</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Login as:</label>
                <select name="role" class="form-control" required>
                    <option value="">Select role</option>
                    <option value="student">Student</option>
                    <option value="instructor">Instructor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>