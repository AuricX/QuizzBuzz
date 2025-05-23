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
            <h5 class="text-light">Categories</h5>
            <div class="list-group">
                <?php
                $categories = [
                    'Mathematics',
                    'Science',
                    'History',
                    'Literature',
                    'Computer Science'
                ];
                foreach ($categories as $category): ?>
                    <a href="/category/<?php echo strtolower(str_replace(' ', '-', $category)); ?>" 
                       class="list-group-item list-group-item-action">
                        <?php echo $category; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div> 