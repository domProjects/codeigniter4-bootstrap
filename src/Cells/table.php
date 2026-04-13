<?php if ($wrapperClasses !== ''): ?><div class="<?= esc($wrapperClasses) ?>"><?php endif; ?>
<table class="<?= esc($tableClasses) ?>">
    <?php if ($captionHtml !== ''): ?>
        <caption<?= $captionTop ? ' class="caption-top"' : '' ?>><?= $captionHtml ?></caption>
    <?php endif; ?>
    <?php if ($headers !== []): ?>
        <thead<?= $headClasses === '' ? '' : ' class="' . esc($headClasses) . '"' ?>>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <th scope="<?= esc($header['scope']) ?>"<?= $header['classes'] === '' ? '' : ' class="' . esc($header['classes']) . '"' ?>><?= $header['html'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
    <?php endif; ?>
    <?php if ($rows !== []): ?>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr<?= $row['classes'] === '' ? '' : ' class="' . esc($row['classes']) . '"' ?>>
                    <?php foreach ($row['cells'] as $cell): ?>
                        <?php
                        $attributeHtml = '';

                        foreach ($cell['attributes'] as $name => $value) {
                            $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
                        }
                        ?>
                        <<?= esc($cell['tag']) ?><?= $cell['classes'] === '' ? '' : ' class="' . esc($cell['classes']) . '"' ?><?= $cell['scope'] === '' ? '' : ' scope="' . esc($cell['scope']) . '"' ?><?= $attributeHtml ?>><?= $cell['html'] ?></<?= esc($cell['tag']) ?>>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    <?php else: ?>
        <tbody>
            <tr>
                <td colspan="<?= esc((string) $emptyRow['colspan']) ?>"<?= $emptyRow['classes'] === '' ? '' : ' class="' . esc($emptyRow['classes']) . '"' ?>><?= $emptyRow['messageHtml'] ?></td>
            </tr>
        </tbody>
    <?php endif; ?>
    <?php if ($footer !== []): ?>
        <tfoot>
            <tr>
                <?php foreach ($footer as $cell): ?>
                    <td<?= $cell['classes'] === '' ? '' : ' class="' . esc($cell['classes']) . '"' ?>><?= $cell['html'] ?></td>
                <?php endforeach; ?>
            </tr>
        </tfoot>
    <?php endif; ?>
</table>
<?php if ($wrapperClasses !== ''): ?></div><?php endif; ?>
