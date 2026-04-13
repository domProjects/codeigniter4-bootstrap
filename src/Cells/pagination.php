<nav aria-label="<?= esc($label) ?>">
    <ul class="<?= esc($paginationClasses) ?>">
        <?php foreach ($items as $item): ?>
            <li class="<?= esc($item['itemClasses']) ?>">
                <?php
                $linkAttributeHtml = '';

                foreach ($item['linkAttributes'] as $name => $value) {
                    $linkAttributeHtml .= ' ' . $name . '="' . esc($value) . '"';
                }
                ?>
                <<?= esc($item['linkTag']) ?> class="<?= esc($item['linkClasses']) ?>"<?= $linkAttributeHtml ?>><?= $item['labelHtml'] ?></<?= esc($item['linkTag']) ?>>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
