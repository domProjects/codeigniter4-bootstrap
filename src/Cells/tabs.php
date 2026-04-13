<ul class="<?= esc($navClasses) ?>" role="tablist">
    <?php foreach ($items as $item): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link<?= $item['active'] ? ' active' : '' ?>" id="<?= esc((string) $item['tabId']) ?>" data-bs-toggle="tab" data-bs-target="#<?= esc((string) $item['paneId']) ?>" type="button" role="tab" aria-controls="<?= esc((string) $item['paneId']) ?>" aria-selected="<?= $item['active'] ? 'true' : 'false' ?>"<?= $item['disabled'] ? ' disabled' : '' ?>>
                <?= (string) $item['labelHtml'] ?>
            </button>
        </li>
    <?php endforeach; ?>
</ul>
<div class="<?= esc($contentClasses) ?>">
    <?php foreach ($items as $item): ?>
        <div class="tab-pane<?= $fade ? ' fade' : '' ?><?= $item['active'] ? ($fade ? ' show active' : ' active') : '' ?>" id="<?= esc((string) $item['paneId']) ?>" role="tabpanel" aria-labelledby="<?= esc((string) $item['tabId']) ?>" tabindex="0">
            <?= (string) $item['contentHtml'] ?>
        </div>
    <?php endforeach; ?>
</div>
