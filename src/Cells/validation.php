<?php
$attributeHtml = '';

foreach ($attributes as $name => $value) {
    $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<div<?= $attributeHtml ?>><?= $html ?></div>
