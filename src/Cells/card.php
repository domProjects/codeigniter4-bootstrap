<div class="<?= esc($cardClasses) ?>">
    <?php if ($imageUrl !== '' && $imagePosition === 'top'): ?>
        <img src="<?= esc($imageUrl) ?>" class="card-img-top" alt="<?= esc($imageAlt) ?>">
    <?php endif; ?>

    <?php if ($headerHtml !== ''): ?>
        <div class="card-header"><?= $headerHtml ?></div>
    <?php endif; ?>

    <div class="<?= esc($bodyClasses) ?>">
        <?php if ($titleHtml !== ''): ?>
            <<?= esc($titleTag) ?> class="card-title"><?= $titleHtml ?></<?= esc($titleTag) ?>>
        <?php endif; ?>

        <?php if ($subtitleHtml !== ''): ?>
            <h6 class="card-subtitle mb-2 text-body-secondary"><?= $subtitleHtml ?></h6>
        <?php endif; ?>

        <?php if ($contentHtml !== ''): ?>
            <p class="card-text"><?= $contentHtml ?></p>
        <?php endif; ?>
    </div>

    <?php if ($footerHtml !== ''): ?>
        <div class="card-footer"><?= $footerHtml ?></div>
    <?php endif; ?>

    <?php if ($imageUrl !== '' && $imagePosition === 'bottom'): ?>
        <img src="<?= esc($imageUrl) ?>" class="card-img-bottom" alt="<?= esc($imageAlt) ?>">
    <?php endif; ?>
</div>
