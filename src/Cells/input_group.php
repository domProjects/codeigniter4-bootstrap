<?php
$groupAttributeHtml = '';

foreach ($attributes as $name => $value) {
    $groupAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<div<?= $groupAttributeHtml ?>>
    <?php foreach ($items as $item): ?>
        <?php
        $attributeHtml = '';
        $labelAttributeHtml = '';
        $validFeedbackAttributeHtml = '';
        $invalidFeedbackAttributeHtml = '';

        foreach ($item['attributes'] as $name => $value) {
            $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }

        foreach (($item['labelAttributes'] ?? []) as $name => $value) {
            $labelAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }

        foreach (($item['validFeedbackAttributes'] ?? []) as $name => $value) {
            $validFeedbackAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }

        foreach (($item['invalidFeedbackAttributes'] ?? []) as $name => $value) {
            $invalidFeedbackAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
        }
        ?>
        <?php if ($item['kind'] === 'addon'): ?>
            <<?= esc($item['tag']) ?><?= $attributeHtml ?>><?= $item['html'] ?></<?= esc($item['tag']) ?>>
        <?php elseif ($item['kind'] === 'check'): ?>
            <?php
            $wrapperAttributeHtml = '';

            foreach ($item['wrapperAttributes'] as $name => $value) {
                $wrapperAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }
            ?>
            <div<?= $wrapperAttributeHtml ?>><input<?= $attributeHtml ?>></div>
            <?php if (($item['validFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $validFeedbackAttributeHtml ?>><?= $item['validFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['invalidFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $invalidFeedbackAttributeHtml ?>><?= $item['invalidFeedbackHtml'] ?></div>
            <?php endif; ?>
        <?php elseif ($item['kind'] === 'select'): ?>
            <?php if (($item['wrapperClasses'] ?? '') !== ''): ?><div class="<?= esc($item['wrapperClasses']) ?>"><?php endif; ?>
            <<?= esc($item['tag']) ?><?= $attributeHtml ?>>
                <?php foreach ($item['options'] as $option): ?>
                    <option value="<?= esc($option['value']) ?>"<?= $option['selected'] === '' ? '' : ' selected="selected"' ?>><?= $option['html'] ?></option>
                <?php endforeach; ?>
            </<?= esc($item['tag']) ?>>
            <?php if (($item['labelHtml'] ?? '') !== ''): ?><label<?= $labelAttributeHtml ?>><?= $item['labelHtml'] ?></label><?php endif; ?>
            <?php if (($item['validFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $validFeedbackAttributeHtml ?>><?= $item['validFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['invalidFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $invalidFeedbackAttributeHtml ?>><?= $item['invalidFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['wrapperClasses'] ?? '') !== ''): ?></div><?php endif; ?>
        <?php elseif ($item['kind'] === 'textarea' || $item['kind'] === 'button'): ?>
            <?php if (($item['wrapperClasses'] ?? '') !== ''): ?><div class="<?= esc($item['wrapperClasses']) ?>"><?php endif; ?>
            <<?= esc($item['tag']) ?><?= $attributeHtml ?>><?= $item['html'] ?></<?= esc($item['tag']) ?>>
            <?php if (($item['labelHtml'] ?? '') !== ''): ?><label<?= $labelAttributeHtml ?>><?= $item['labelHtml'] ?></label><?php endif; ?>
            <?php if (($item['validFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $validFeedbackAttributeHtml ?>><?= $item['validFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['invalidFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $invalidFeedbackAttributeHtml ?>><?= $item['invalidFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['wrapperClasses'] ?? '') !== ''): ?></div><?php endif; ?>
        <?php else: ?>
            <?php if (($item['wrapperClasses'] ?? '') !== ''): ?><div class="<?= esc($item['wrapperClasses']) ?>"><?php endif; ?>
            <input<?= $attributeHtml ?>>
            <?php if (($item['labelHtml'] ?? '') !== ''): ?><label<?= $labelAttributeHtml ?>><?= $item['labelHtml'] ?></label><?php endif; ?>
            <?php if (($item['validFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $validFeedbackAttributeHtml ?>><?= $item['validFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['invalidFeedbackHtml'] ?? '') !== ''): ?>
                <div<?= $invalidFeedbackAttributeHtml ?>><?= $item['invalidFeedbackHtml'] ?></div>
            <?php endif; ?>
            <?php if (($item['wrapperClasses'] ?? '') !== ''): ?></div><?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
