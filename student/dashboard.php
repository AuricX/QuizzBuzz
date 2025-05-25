<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Student Dashboard - QuizzBuzz';
$currentPage = 'home';

require_once __DIR__ . '/../db/conx.php';
// Start output buffering to capture content for the layout
ob_start();

$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>