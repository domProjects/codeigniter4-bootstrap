<?php
$wrapperAttributeHtml = '';

foreach ($wrapperAttributes as $name => $value) {
    $wrapperAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<div<?= $wrapperAttributeHtml ?>>
    <?php foreach ($groups as $group): ?>
        <?php
        $groupAttributeHtml = '';

        foreach ($group['attributes'] as $name => $value) {
            $groupAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }
        ?>
        <div<?= $groupAttributeHtml ?>>
            <?php foreach ($group['items'] as $item): ?>
                <?php
                $attributeHtml = '';

                foreach ($item['attributes'] as $name => $value) {
                    $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
                }
                ?>
                <<?= esc((string) $item['tag']) ?><?= $attributeHtml ?>><?= (string) $item['html'] ?></<?= esc((string) $item['tag']) ?>>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
