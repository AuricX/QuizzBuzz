<?php
// Serve static files directly when using PHP's built-in server
if (php_sapi_name() === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false; // Let the server handle the static file
    }
}

session_start();
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$role = $_SESSION['role'] ?? null;

$routes = [
    '' => ['file' => 'index.php', 'role' => 'any'],
    'student/courses' => ['file' => 'student/courses.php', 'role' => 'student'],
    'student/course' => ['file' => 'student/course.php', 'role' => 'student'],
    'student/quiz' => ['file' => 'student/quiz.php', 'role' => 'student'],
    'student/enroll' => ['file' => 'student/enroll.php', 'role' => 'student'],
    'student/dashboard' => ['file' => 'student/dashboard.php', 'role' => 'student'],
    'instructor/courses' => ['file' => 'instructor/courses.php', 'role' => 'instructor'],
    'instructor/dashboard' => ['file' => 'instructor/dashboard.php', 'role' => 'instructor'],
    // ... add more routes as needed
];

if (!isset($_SESSION['user_id'])) {
    // Allow access to login.php without being logged in
    if ($uri === 'login.php' || $uri === 'login') {
        require 'login.php';
        exit;
    }
    header('Location: /login.php');
    exit;
}

if (isset($routes[$uri])) {
    $route = $routes[$uri];
    if ($route['role'] !== $role) {
        // Redirect to their own dashboard or show 403
        header('Location: /' . $role . '/dashboard.php');
        exit;
    }
    require $route['file'];
} else {
    http_response_code(404);
    echo '404 Not Found';
} 