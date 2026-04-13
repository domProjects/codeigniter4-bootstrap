<?php
$attributeHtml = '';

foreach ($attributes as $name => $value) {
    $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<button<?= $attributeHtml ?>></button>
