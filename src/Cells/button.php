<?php
$attributeHtml = '';

foreach ($attributes as $name => $value) {
    $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<<?= esc($buttonTag) ?><?= $attributeHtml ?>><?= $buttonHtml ?></<?= esc($buttonTag) ?>>
