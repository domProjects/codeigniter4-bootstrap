<span class="<?= esc($badgeClasses) ?>">
    <?= $contentHtml ?>
    <?php if ($hiddenTextHtml !== ''): ?>
        <span class="visually-hidden"><?= $hiddenTextHtml ?></span>
    <?php endif; ?>
</span>
