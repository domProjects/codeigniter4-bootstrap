<<?= esc($groupTag) ?> class="<?= esc($groupClasses) ?>">
    <?php foreach ($items as $item): ?>
        <?php
        $attributeHtml = '';

        foreach ($item['attributes'] as $name => $value) {
            $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }
        ?>
        <<?= esc($item['tag']) ?><?= $attributeHtml ?>>
            <?= $item['labelHtml'] ?>
            <?php if ($item['badgeHtml'] !== ''): ?>
                <span class="<?= esc($item['badgeClasses']) ?>"><?= $item['badgeHtml'] ?></span>
            <?php endif; ?>
        </<?= esc($item['tag']) ?>>
    <?php endforeach; ?>
</<?= esc($groupTag) ?>>
