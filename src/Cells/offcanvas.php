<div class="<?= esc($offcanvasClasses) ?>" tabindex="-1" id="<?= esc($offcanvasId) ?>" aria-labelledby="<?= esc($offcanvasId) ?>-label"<?= $show ? ' aria-modal="true"' : '' ?><?= $show ? ' style="visibility: visible;"' : '' ?> data-bs-scroll="<?= $scroll ? 'true' : 'false' ?>" data-bs-backdrop="<?= esc($backdrop) ?>"<?= $theme !== '' ? ' data-bs-theme="' . esc($theme) . '"' : '' ?>>
    <?php if ($titleHtml !== '' || $closeButton): ?>
        <div class="<?= esc($headerClasses) ?>">
            <?php if ($titleHtml !== ''): ?>
                <h5 class="offcanvas-title" id="<?= esc($offcanvasId) ?>-label"><?= $titleHtml ?></h5>
            <?php endif; ?>
            <?php if ($closeButton): ?>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="<?= esc($bodyClasses) ?>"><?= $bodyHtml ?></div>
</div>
