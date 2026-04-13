<div class="<?= esc($toastClasses) ?>" role="<?= esc($role) ?>" aria-live="<?= esc($ariaLive) ?>" aria-atomic="<?= esc($ariaAtomic) ?>" data-bs-autohide="<?= $autoHide ? 'true' : 'false' ?>" data-bs-delay="<?= esc($delay) ?>">
    <?php if ($titleHtml !== '' || $subtitleHtml !== '' || $closeButton): ?>
        <div class="<?= esc($headerClasses) ?>">
            <?php if ($titleHtml !== ''): ?>
                <strong class="me-auto"><?= $titleHtml ?></strong>
            <?php endif; ?>
            <?php if ($subtitleHtml !== ''): ?>
                <small class="text-body-secondary"><?= $subtitleHtml ?></small>
            <?php endif; ?>
            <?php if ($closeButton): ?>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="<?= esc($bodyClasses) ?>"><?= $bodyHtml ?></div>
</div>
