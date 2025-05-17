<?php
/**
 * @param string $id Unique modal ID
 * @param string $title Modal title
 * @param string $content Modal content
 * @param array $buttons Modal buttons configuration
 * @param string $size Modal size (sm, lg, xl)
 */
function renderModal($id, $title, $content, $buttons = [], $size = '') {
    $sizeClass = $size ? "modal-$size" : "";
?>
    <div class="modal fade" id="<?php echo htmlspecialchars($id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog <?php echo $sizeClass; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($title); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $content; ?>
                </div>
                <?php if (!empty($buttons)): ?>
                    <div class="modal-footer">
                        <?php foreach ($buttons as $button): ?>
                            <button type="button" 
                                    class="btn <?php echo $button['class'] ?? 'btn-secondary'; ?>"
                                    <?php if (!empty($button['dismiss'])): ?>data-bs-dismiss="modal"<?php endif; ?>
                                    <?php if (!empty($button['onclick'])): ?>onclick="<?php echo htmlspecialchars($button['onclick']); ?>"<?php endif; ?>>
                                <?php if (!empty($button['icon'])): ?>
                                    <i class="bi bi-<?php echo $button['icon']; ?> me-1"></i>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($button['text']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}
?> 