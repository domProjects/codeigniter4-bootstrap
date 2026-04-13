<div class="<?= esc($modalClasses) ?>" id="<?= esc($modalId) ?>" tabindex="-1" aria-hidden="<?= $show ? 'false' : 'true' ?>"<?= $show ? ' style="display:block;"' : '' ?><?= $staticBackdrop ? ' data-bs-backdrop="static"' : '' ?><?= $keyboard ? '' : ' data-bs-keyboard="false"' ?>>
    <div class="<?= esc($dialogClasses) ?>">
        <div class="modal-content">
            <?php if ($titleHtml !== '' || $closeButton): ?>
                <div class="modal-header">
                    <?php if ($titleHtml !== ''): ?>
                        <h1 class="modal-title fs-5"><?= $titleHtml ?></h1>
                    <?php endif; ?>
                    <?php if ($closeButton): ?>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="modal-body"><?= $bodyHtml ?></div>
            <?php if ($footerHtml !== ''): ?>
                <div class="modal-footer"><?= $footerHtml ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
