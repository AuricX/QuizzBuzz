<div class="sidebar bg-dark border-end flex-shrink-0" style="width: 280px; height: 100vh;">
    <div class="d-flex flex-column p-3 h-100">
        <div class="mb-4">
            <h5 class="text-light">Quick Actions</h5>
            <div class="list-group">
                <?php if ($_SESSION['role'] === 'student'): ?>
                    <a href="/student/enroll" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Enroll in Course
                    </a>
                <?php elseif ($_SESSION['role'] === 'instructor'): ?>
                    <a href="/instructor/create_course" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Create Course
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mb-4">
            <h5 class="text-light">Courses</h5>
            <div class="list-group">
                <?php
                try {
                    if ($_SESSION['role'] === 'student') {
                        $stmt = $database->prepare("
                            SELECT c.course_id, c.title
                            FROM course c
                            JOIN enrollment e ON c.course_id = e.course_id
                            WHERE e.student_id = :user_id
                            ORDER BY c.title
                        ");
                        $linkPrefix = '/student/course?id=';
                    } else if ($_SESSION['role'] === 'instructor') {
                        $stmt = $database->prepare("
                            SELECT course_id, title
                            FROM course
                            WHERE teacher_id = :user_id
                            ORDER BY title
                        ");
                        $linkPrefix = '/instructor/course?id=';
                    }
                    $stmt->execute(['user_id' => $_SESSION['user_id']]);
                    while ($course = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <a href="<?php echo $linkPrefix . $course['course_id']; ?>" 
                       class="list-group-item list-group-item-action">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </a>
                <?php
                    endwhile;
                } catch(PDOException $e) {
                    error_log("Database Error: " . $e->getMessage());
                    echo '<div class="alert alert-danger">Unable to load courses.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div> 