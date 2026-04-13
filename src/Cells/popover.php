<?php
$attributeHtml = '';

foreach ($attributes as $name => $value) {
    $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<<?= esc($tag) ?><?= $attributeHtml ?>><?= $labelHtml ?></<?= esc($tag) ?>>
