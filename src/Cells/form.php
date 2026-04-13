<?php
$formAttributeHtml = '';

foreach ($formAttributes as $name => $value) {
    $formAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
}
?>
<form<?= $formAttributeHtml ?>>
    <?php if ($fieldsetDisabled): ?>
        <fieldset disabled="disabled">
    <?php endif; ?>
    <?php foreach ($items as $item): ?>
        <?php if (($item['columnClasses'] ?? '') !== '' && $item['kind'] !== 'hidden'): ?><div class="<?= esc($item['columnClasses']) ?>"><?php endif; ?>
        <?php if ($item['kind'] === 'hidden'): ?>
            <?php
            $attributeHtml = '';

            foreach ($item['attributes'] as $name => $value) {
                $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }
            ?>
            <input<?= $attributeHtml ?>>
        <?php elseif ($item['kind'] === 'button'): ?>
            <?php
            $attributeHtml = '';

            foreach ($item['attributes'] as $name => $value) {
                $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }
            ?>
            <div<?= $item['wrapperClasses'] === '' ? '' : ' class="' . esc($item['wrapperClasses']) . '"' ?>><button<?= $attributeHtml ?>><?= $item['labelHtml'] ?></button></div>
        <?php elseif ($item['kind'] === 'check'): ?>
            <?php
            $attributeHtml = '';
            $labelAttributeHtml = '';
            $helpAttributeHtml = '';
            $validFeedbackAttributeHtml = '';
            $invalidFeedbackAttributeHtml = '';

            foreach ($item['attributes'] as $name => $value) {
                $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['labelAttributes'] as $name => $value) {
                $labelAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['helpAttributes'] as $name => $value) {
                $helpAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['validFeedbackAttributes'] as $name => $value) {
                $validFeedbackAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['invalidFeedbackAttributes'] as $name => $value) {
                $invalidFeedbackAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }
            ?>
            <div class="<?= esc($item['wrapperClasses']) ?>">
                <input<?= $attributeHtml ?>>
                <?php if ($item['labelHtml'] !== ''): ?>
                    <label<?= $labelAttributeHtml ?>><?= $item['labelHtml'] ?></label>
                <?php endif; ?>
                <?php if ($item['helpHtml'] !== ''): ?>
                    <div<?= $helpAttributeHtml ?>><?= $item['helpHtml'] ?></div>
                <?php endif; ?>
                <?php if ($item['validFeedbackHtml'] !== ''): ?>
                    <div<?= $validFeedbackAttributeHtml ?>><?= $item['validFeedbackHtml'] ?></div>
                <?php endif; ?>
                <?php if ($item['invalidFeedbackHtml'] !== ''): ?>
                    <div<?= $invalidFeedbackAttributeHtml ?>><?= $item['invalidFeedbackHtml'] ?></div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php
            $attributeHtml = '';
            $labelAttributeHtml = '';
            $helpAttributeHtml = '';
            $validFeedbackAttributeHtml = '';
            $invalidFeedbackAttributeHtml = '';

            foreach ($item['attributes'] as $name => $value) {
                $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['labelAttributes'] as $name => $value) {
                $labelAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['helpAttributes'] as $name => $value) {
                $helpAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['validFeedbackAttributes'] as $name => $value) {
                $validFeedbackAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }

            foreach ($item['invalidFeedbackAttributes'] as $name => $value) {
                $invalidFeedbackAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
            }
            ?>
            <div class="<?= esc($item['wrapperClasses']) ?>">
                <?php if ($item['labelHtml'] !== '' && ! $item['labelAfterControl']): ?>
                    <label<?= $labelAttributeHtml ?>><?= $item['labelHtml'] ?></label>
                <?php endif; ?>
                <?php if ($item['kind'] === 'select'): ?>
                    <<?= esc($item['tag']) ?><?= $attributeHtml ?>>
                        <?php foreach ($item['options'] as $option): ?>
                            <option value="<?= esc($option['value']) ?>"<?= $option['selected'] === '' ? '' : ' selected="selected"' ?>><?= $option['html'] ?></option>
                        <?php endforeach; ?>
                    </<?= esc($item['tag']) ?>>
                <?php elseif ($item['tag'] === 'textarea'): ?>
                    <<?= esc($item['tag']) ?><?= $attributeHtml ?>><?= $item['controlHtml'] ?></<?= esc($item['tag']) ?>>
                <?php else: ?>
                    <input<?= $attributeHtml ?>>
                <?php endif; ?>
                <?php if ($item['labelHtml'] !== '' && $item['labelAfterControl']): ?>
                    <label<?= $labelAttributeHtml ?>><?= $item['labelHtml'] ?></label>
                <?php endif; ?>
                <?php if ($item['helpHtml'] !== ''): ?>
                    <div<?= $helpAttributeHtml ?>><?= $item['helpHtml'] ?></div>
                <?php endif; ?>
                <?php if ($item['validFeedbackHtml'] !== ''): ?>
                    <div<?= $validFeedbackAttributeHtml ?>><?= $item['validFeedbackHtml'] ?></div>
                <?php endif; ?>
                <?php if ($item['invalidFeedbackHtml'] !== ''): ?>
                    <div<?= $invalidFeedbackAttributeHtml ?>><?= $item['invalidFeedbackHtml'] ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (($item['columnClasses'] ?? '') !== '' && $item['kind'] !== 'hidden'): ?></div><?php endif; ?>
    <?php endforeach; ?>
    <?php if ($fieldsetDisabled): ?>
        </fieldset>
    <?php endif; ?>
</form>
