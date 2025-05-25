<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: /login.php');
    exit;
}

$pageTitle = 'Enroll in Courses - QuizzBuzz';
$currentPage = 'enroll';

require_once __DIR__ . '/../db/conx.php';
require_once __DIR__ . '/../components/card.php';

// Start output buffering to capture content for the layout
ob_start();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Available Courses</h2>
        <div class="row">
            <?php
            try {
                // Get courses that the student is not enrolled in
                $stmt = $database->prepare("
                    SELECT c.course_id, c.title, c.description, c.level, c.price, t.name AS instructor_name, COUNT(q.quiz_id) AS quiz_count
                    FROM course c
                    JOIN teacher t ON c.teacher_id = t.teacher_id
                    LEFT JOIN quiz q ON c.course_id = q.course_id
                    WHERE c.course_id NOT IN (
                        SELECT course_id 
                        FROM enrollment 
                        WHERE student_id = :student_id
                    )
                    GROUP BY c.course_id, c.title, c.description, c.level, c.price, t.name
                    ORDER BY c.course_id DESC
                ");
                
                $stmt->execute(['student_id' => $_SESSION['user_id']]);

                if ($stmt->rowCount() === 0) {
                    echo '<div class="col-12"><div class="alert alert-info">There are no courses available for enrollment at this time.</div></div>';
                }

                while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $actions = [
                        ['url' => '#', 'text' => 'Enroll Now', 'icon' => 'plus-circle', 'class' => 'btn-primary enroll-btn', 'data' => [
                            'course-id' => $course['course_id'],
                            'course-title' => $course['title'],
                            'course-price' => $course['price']
                        ]]
                    ];
                    $footer = '<div class="text-muted">'
                        . '<small>'
                        . '<i class="bi bi-person"></i> Instructor: ' . htmlspecialchars($course['instructor_name']) . '<br>'
                        . '<i class="bi bi-bar-chart"></i> Level: ' . htmlspecialchars($course['level']) . '<br>'
                        . '<i class="bi bi-question-circle"></i> Quizzes: ' . $course['quiz_count'] . '<br>'
                        . '<i class="bi bi-currency-dollar"></i> Price: $' . number_format($course['price'], 2)
                        . '</small>'
                        . '</div>';
                    renderCard(
                        $course['title'],
                        $course['description'],
                        $footer,
                        null,
                        $actions
                    );
                }
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                echo '<div class="alert alert-danger">Unable to load courses. Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll in Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>You are about to enroll in: <strong id="modalCourseTitle"></strong></p>
                <p>Price: $<span id="modalCoursePrice"></span></p>
                <form id="paymentForm">
                    <input type="hidden" id="courseId" name="course_id">
                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" maxlength="19" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiryDate" maxlength="5" placeholder="MM/YY">
                        </div>
                        <div class="col">
                            <label class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" maxlength="3" placeholder="123">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPayment">Complete Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    
    // Handle enroll button clicks
    document.querySelectorAll('.enroll-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const courseId = this.dataset.courseId;
            const courseTitle = this.dataset.courseTitle;
            const coursePrice = this.dataset.coursePrice;
            
            document.getElementById('modalCourseTitle').textContent = courseTitle;
            document.getElementById('modalCoursePrice').textContent = coursePrice;
            document.getElementById('courseId').value = courseId;
            
            paymentModal.show();
        });
    });
    
    // Handle payment confirmation
    document.getElementById('confirmPayment').addEventListener('click', function() {
        const courseId = document.getElementById('courseId').value;
        const cardNumber = document.getElementById('cardNumber').value.replace(/\s+/g, '');
        const expiryDate = document.getElementById('expiryDate').value;
        const cvv = document.getElementById('cvv').value;
        // Validate card number (16 digits)
        if (!/^\d{16}$/.test(cardNumber)) {
            alert('Please enter a valid 16-digit card number.');
            return;
        }
        // Validate expiry date (MM/YY)
        if (!/^(0[1-9]|1[0-2])\/(\d{2})$/.test(expiryDate)) {
            alert('Please enter a valid expiry date in MM/YY format.');
            return;
        }
        // Validate CVV (3 digits)
        if (!/^\d{3}$/.test(cvv)) {
            alert('Please enter a valid 3-digit CVV.');
            return;
        }
        // Send enrollment request
        fetch('/student/enroll_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                course_id: courseId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                paymentModal.hide();
                alert('Successfully enrolled in the course!');
                window.location.reload();
            } else {
                alert(data.message || 'Failed to enroll in the course.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your enrollment.');
        });
    });
});
</script>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . '/../layouts/main.php';
?> 