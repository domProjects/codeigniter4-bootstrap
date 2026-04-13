<div<?= $wrapperClasses === '' ? '' : ' class="' . esc($wrapperClasses) . '"' ?>>
    <?php foreach ($items as $item): ?>
        <div<?= $item['columnClasses'] === '' ? '' : ' class="' . esc($item['columnClasses']) . '"' ?>>
            <div class="<?= esc($item['cardClasses']) ?>">
                <div class="card-body">
                    <?php if ($item['labelHtml'] !== ''): ?><p class="text-uppercase small fw-semibold mb-2"><?= $item['labelHtml'] ?></p><?php endif; ?>
                    <?php if ($item['valueHtml'] !== ''): ?><div class="display-6 fw-bold lh-1"><?= $item['valueHtml'] ?></div><?php endif; ?>
                    <?php if ($item['descriptionHtml'] !== ''): ?><p class="mb-0 mt-3"><?= $item['descriptionHtml'] ?></p><?php endif; ?>
                    <?php if ($item['metaHtml'] !== ''): ?><div class="small mt-2 opacity-75"><?= $item['metaHtml'] ?></div><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
