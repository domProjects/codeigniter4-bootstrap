<ul class="<?= esc($navClasses) ?>"<?= $hasPanes ? ' role="tablist"' : '' ?>>
    <?php foreach ($items as $item): ?>
        <li class="nav-item" role="presentation">
            <?php
            $attributeHtml = '';

            foreach ($item['attributes'] as $name => $value) {
                $attributeHtml .= ' ' . $name . '="' . esc((string) $value) . '"';
            }
            ?>
            <a<?= $attributeHtml ?>><?= (string) $item['labelHtml'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
<?php if ($hasPanes): ?>
    <div class="<?= esc($contentClasses) ?>">
        <?php foreach ($items as $item): ?>
            <div class="tab-pane<?= $fade ? ' fade' : '' ?><?= $item['active'] ? ($fade ? ' show active' : ' active') : '' ?>" id="<?= esc((string) $item['paneId']) ?>" role="tabpanel" aria-labelledby="<?= esc((string) $item['itemId']) ?>" tabindex="0">
                <?= (string) $item['contentHtml'] ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
