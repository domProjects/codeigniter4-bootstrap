<div class="<?= esc($progressClasses) ?>"<?= $progressStyle !== '' ? ' style="' . esc($progressStyle) . '"' : '' ?>>
    <?php foreach ($bars as $bar): ?>
        <div class="<?= esc((string) $bar['classes']) ?>" role="progressbar" style="width: <?= esc((string) $bar['percent']) ?>%;" aria-valuenow="<?= esc((string) $bar['value']) ?>" aria-valuemin="<?= esc((string) $bar['min']) ?>" aria-valuemax="<?= esc((string) $bar['max']) ?>"><?= (string) $bar['label'] ?></div>
    <?php endforeach; ?>
</div>
