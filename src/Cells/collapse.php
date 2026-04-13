<div class="<?= esc($collapseClasses) ?>" id="<?= esc($collapseId) ?>">
    <?php if ($wrapBody): ?>
        <div class="<?= esc($bodyClasses) ?>"><?= $bodyHtml ?></div>
    <?php else: ?>
        <?= $bodyHtml ?>
    <?php endif; ?>
</div>
