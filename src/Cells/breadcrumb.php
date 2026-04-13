<?php
$breadcrumbAttributeHtml = '';

foreach ($breadcrumbAttributes as $name => $value) {
    $breadcrumbAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<nav aria-label="<?= esc($label) ?>">
    <ol<?= $breadcrumbAttributeHtml ?>>
        <?php foreach ($items as $item): ?>
            <li class="<?= esc($item['itemClasses']) ?>"<?= $item['active'] ? ' aria-current="' . esc($item['current']) . '"' : '' ?>>
                <?php if (! $item['active'] && $item['url'] !== ''): ?><a href="<?= esc($item['url']) ?>"<?= $item['linkClasses'] === '' ? '' : ' class="' . esc($item['linkClasses']) . '"' ?>><?= $item['label'] ?></a><?php else: ?><?= $item['label'] ?><?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
