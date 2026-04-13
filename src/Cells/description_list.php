<dl<?= $listClasses === '' ? '' : ' class="' . esc($listClasses) . '"' ?>>
    <?php foreach ($items as $item): ?>
        <dt<?= $item['termClasses'] === '' ? '' : ' class="' . esc($item['termClasses']) . '"' ?>><?= $item['termHtml'] ?></dt>
        <dd<?= $item['descriptionClasses'] === '' ? '' : ' class="' . esc($item['descriptionClasses']) . '"' ?>><?= $item['descriptionHtml'] ?></dd>
    <?php endforeach; ?>
</dl>
