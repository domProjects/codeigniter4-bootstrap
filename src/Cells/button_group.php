<?php
$wrapperAttributeHtml = '';

foreach ($wrapperAttributes as $name => $value) {
    $wrapperAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<div<?= $wrapperAttributeHtml ?>>
    <?php foreach ($items as $item): ?>
        <?php
        $attributeHtml = '';

        foreach ($item['attributes'] as $name => $value) {
            $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }
        ?>
        <<?= esc((string) $item['tag']) ?><?= $attributeHtml ?>><?= (string) $item['html'] ?></<?= esc((string) $item['tag']) ?>>
    <?php endforeach; ?>
</div>
