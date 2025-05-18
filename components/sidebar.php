<div class="sidebar bg-dark border-end" style="width: 280px; min-height: 100vh;">
    <div class="d-flex flex-column p-3">
        <div class="mb-4">
            <h5 class="text-light">Quick Actions</h5>
            <div class="list-group">
                <a href="/new-quiz" class="list-group-item list-group-item-action">
                    <i class="bi bi-plus-circle"></i> Enroll in a Course
                </a>
                <a href="/my-quizzes" class="list-group-item list-group-item-action">
                    <i class="bi bi-collection"></i> Upcoming Quizzes
                </a>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="text-light">Courses</h5>
            <div class="list-group">
                <?php
                $courses = [
                    'CSCI390',
                    'CSCI380',
                    'CSCI378',
                    'CSCI351',
                    'CSCI392',
                    'ENGL251'
                ];
                foreach ($courses as $course): ?>
                    <a href="/course/<?php echo strtolower(str_replace(' ', '-', $course)); ?>"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-mortarboard"></i>
                        <?php echo $course; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>