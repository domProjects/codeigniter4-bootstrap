<div<?= $wrapperClasses !== '' ? ' class="' . esc($wrapperClasses) . '"' : '' ?>>
    <?php foreach ($items as $itemClasses): ?>
        <span class="<?= esc($itemClasses) ?>"></span>
    <?php endforeach; ?>
</div>
