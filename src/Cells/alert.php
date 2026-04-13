<div class="<?= esc($alertClasses) ?>" role="<?= esc($role) ?>">
    <?php if ($headingHtml !== ''): ?>
        <h4 class="alert-heading"><?= $headingHtml ?></h4>
    <?php endif; ?>

    <?= $contentHtml ?>

    <?php if ($dismissible): ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= esc($closeLabel) ?>"></button>
    <?php endif; ?>
</div>
