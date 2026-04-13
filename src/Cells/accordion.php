<div class="<?= esc($accordionClasses) ?>" id="<?= esc($accordionId) ?>">
    <?php foreach ($items as $item): ?>
        <div class="<?= esc((string) $item['itemClasses']) ?>">
            <h2 class="accordion-header" id="<?= esc((string) $item['headingId']) ?>">
                <button class="accordion-button<?= $item['active'] ? '' : ' collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= esc((string) $item['collapseId']) ?>" aria-expanded="<?= $item['active'] ? 'true' : 'false' ?>" aria-controls="<?= esc((string) $item['collapseId']) ?>">
                    <?= (string) $item['titleHtml'] ?>
                </button>
            </h2>
            <div id="<?= esc((string) $item['collapseId']) ?>" class="accordion-collapse collapse<?= $item['active'] ? ' show' : '' ?>" aria-labelledby="<?= esc((string) $item['headingId']) ?>"<?= $alwaysOpen ? '' : ' data-bs-parent="#' . esc($accordionId) . '"' ?>>
                <div class="accordion-body"><?= (string) $item['contentHtml'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
