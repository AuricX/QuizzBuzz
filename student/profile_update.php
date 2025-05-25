<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
require_once __DIR__ . '/../db/conx.php';
$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
if (!$username || !$email) {
    echo json_encode(['success' => false, 'message' => 'Username and email are required.']);
    exit;
}
try {
    $params = [
        'name' => $username,
        'email' => $email,
        'student_id' => $_SESSION['user_id']
    ];
    $set = 'name = :name, email = :email';
    if ($password) {
        $set .= ', password_hash = :password';
        $params['password'] = $password; // For demo, store as plain text
    }
    $stmt = $database->prepare("UPDATE student SET $set WHERE student_id = :student_id");
    $stmt->execute($params);
    // Update session values
    $_SESSION['name'] = $username;
    $_SESSION['email'] = $email;
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} 