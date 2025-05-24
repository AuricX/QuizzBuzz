<?php
/**
 * @param string $title Card title
 * @param string $content Card content
 * @param string|null $footer Optional footer HTML
 * @param string $imageUrl Optional card image
 * @param array $actions Optional action buttons
 */
function renderCard($title, $content, $footer = null, $imageUrl = null, $actions = []) {
?>
    <div class="card mb-4">
        <?php if ($imageUrl): ?>
            <img src="<?php echo htmlspecialchars($imageUrl); ?>" class="card-img-top my-3 rounded-3" alt="<?php echo htmlspecialchars($title); ?>">
        <?php endif; ?>
        
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
            <div class="card-text"><?php echo $content; ?></div>
            
            <?php if (!empty($actions)): ?>
                <div class="mt-3">
                    <?php foreach ($actions as $action): ?>
                        <a href="<?php echo htmlspecialchars($action['url']); ?>" 
                           class="btn <?php echo $action['class'] ?? 'btn-primary'; ?> me-2">
                            <?php if (!empty($action['icon'])): ?>
                                <i class="bi bi-<?php echo $action['icon']; ?>"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($action['text']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($footer && is_string($footer)): ?>
            <div class="card-footer">
                <?php echo $footer; ?>
            </div>
        <?php endif; ?>
    </div>
<?php
}
?> 