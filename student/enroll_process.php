<?php
require_once __DIR__ . '/../db/conx.php';
// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$courseId = isset($data['course_id']) ? (int)$data['course_id'] : 0;
if (!$courseId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid course ID']);
    exit;
}
try {
    // Start transaction
    $database->beginTransaction();
    // Get course price
    $stmt = $database->prepare("SELECT price FROM course WHERE course_id = ?");
    $stmt->execute([$courseId]);
    $price = $stmt->fetchColumn();
    if (!$price) {
        throw new Exception('Course not found');
    }
    // For facade, use a fixed student_id (e.g., 1) or allow from session if present
    $studentId = $_SESSION['user_id'] ?? 1;
    // Check if already enrolled
    $stmt = $database->prepare("SELECT 1 FROM enrollment WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$studentId, $courseId]);
    if ($stmt->fetch()) {
        throw new Exception('You are already enrolled in this course');
    }
    // Record payment
    $stmt = $database->prepare("
        INSERT INTO payment (student_id, course_id, amount)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$studentId, $courseId, $price]);
    // Create enrollment
    $stmt = $database->prepare("
        INSERT INTO enrollment (student_id, course_id)
        VALUES (?, ?)
    ");
    $stmt->execute([$studentId, $courseId]);
    // Commit transaction
    $database->commit();
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $database->rollBack();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 