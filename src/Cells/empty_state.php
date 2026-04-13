<section<?= $wrapperClasses === '' ? '' : ' class="' . esc($wrapperClasses) . '"' ?>>
    <div<?= $bodyClasses === '' ? '' : ' class="' . esc($bodyClasses) . '"' ?><?= $maxWidth === '' ? '' : ' style="max-width: ' . esc($maxWidth) . ';"' ?>>
        <?php if ($titleHtml !== ''): ?><h2 class="h3 mb-3"><?= $titleHtml ?></h2><?php endif; ?>
        <?php if ($contentHtml !== ''): ?><p class="lead text-body-secondary mb-4"><?= $contentHtml ?></p><?php endif; ?>
        <?php if ($actions !== []): ?>
            <div class="d-inline-flex flex-wrap gap-2 justify-content-center">
                <?php foreach ($actions as $action): ?>
                    <?php
                    $attributeHtml = '';

                    foreach ($action['attributes'] as $name => $value) {
                        $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
                    }
                    ?>
                    <<?= esc($action['tag']) ?><?= $attributeHtml ?>><?= $action['html'] ?></<?= esc($action['tag']) ?>>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
