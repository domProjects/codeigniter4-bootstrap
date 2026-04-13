<div class="<?= esc($wrapperClasses) ?>">
    <button class="<?= esc($buttonClasses) ?>" type="button" id="<?= esc($buttonId) ?>" data-bs-toggle="dropdown" aria-expanded="false">
        <?= $labelHtml ?>
    </button>
    <ul class="<?= esc($menuClasses) ?>" aria-labelledby="<?= esc($buttonId) ?>">
        <?php foreach ($items as $item): ?>
            <?php if ($item['type'] === 'divider'): ?>
                <li><hr class="dropdown-divider"></li>
            <?php elseif ($item['type'] === 'header'): ?>
                <li><h6 class="dropdown-header"><?= $item['html'] ?></h6></li>
            <?php elseif ($item['type'] === 'text'): ?>
                <li><span class="dropdown-item-text"><?= $item['html'] ?></span></li>
            <?php else: ?>
                <?php
                $attributeHtml = '';

                foreach ($item['attributes'] as $name => $value) {
                    $attributeHtml .= ' ' . $name . '="' . esc($value) . '"';
                }
                ?>
                <li><<?= esc($item['tag']) ?><?= $attributeHtml ?>><?= $item['html'] ?></<?= esc($item['tag']) ?>></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
