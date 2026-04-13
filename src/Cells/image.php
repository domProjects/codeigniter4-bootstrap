<?php if ($sourceAttrs !== []): ?>
<picture>
    <?php foreach ($sourceAttrs as $attributes): ?>
        <source<?= $attributes ?>>
    <?php endforeach; ?>
<?php endif; ?>
<img src="<?= esc($src) ?>" alt="<?= esc($alt) ?>"<?= $imageClasses === '' ? '' : ' class="' . esc($imageClasses) . '"' ?><?= $imageAttrs ?>>
<?php if ($sourceAttrs !== []): ?>
</picture>
<?php endif; ?>
