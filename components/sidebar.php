<div class="sidebar bg-dark border-end flex-shrink-0" style="width: 280px; height: 100vh;">
    <div class="d-flex flex-column p-3 h-100">
        <div class="mb-4">
            <h5 class="text-light">Quick Actions</h5>
            <div class="list-group">
                <a href="/new-quiz" class="list-group-item list-group-item-action">
                    <i class="bi bi-plus-circle"></i> Create New Quiz
                </a>
                <a href="/my-quizzes" class="list-group-item list-group-item-action">
                    <i class="bi bi-collection"></i> My Quizzes
                </a>
            </div>
        </div>
        
        <div class="mb-4">
            <h5 class="text-light">Courses</h5>
            <div class="list-group">
                <?php
                try {
                    $stmt = $database->query("SELECT id, title FROM courses ORDER BY title");
                    while ($course = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <a href="/course/<?php echo $course['id']; ?>" 
                           class="list-group-item list-group-item-action">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </a>
                    <?php endwhile;
                } catch(PDOException $e) {
                    error_log("Database Error: " . $e->getMessage());
                    echo '<div class="alert alert-danger">Unable to load courses.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div> 