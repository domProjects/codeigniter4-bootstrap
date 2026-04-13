<?php
$navAttributeHtml = '';
$contentAttributeHtml = '';

foreach ($navAttributes as $name => $value) {
    $navAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}

foreach ($contentAttributes as $name => $value) {
    $contentAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<div<?= $wrapperClasses === '' ? '' : ' class="' . esc($wrapperClasses) . '"' ?>>
    <?php if ($navType === 'list-group'): ?>
        <div<?= $navAttributeHtml ?>>
            <?php foreach ($items as $item): ?>
                <a class="<?= esc($item['linkClasses']) ?>" href="#<?= esc($item['id']) ?>"><?= $item['linkHtml'] ?></a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <nav<?= $navAttributeHtml ?>>
            <?php foreach ($items as $item): ?>
                <a class="<?= esc($item['linkClasses']) ?>" href="#<?= esc($item['id']) ?>"><?= $item['linkHtml'] ?></a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>
    <div<?= $contentAttributeHtml ?>>
        <?php foreach ($items as $item): ?>
            <<?= esc($item['headingLevel']) ?> id="<?= esc($item['id']) ?>"><?= $item['headingHtml'] ?></<?= esc($item['headingLevel']) ?>>
            <?php if ($item['contentHtml'] !== ''): ?>
                <p class="<?= esc($item['bodyClasses']) ?>"><?= $item['contentHtml'] ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
