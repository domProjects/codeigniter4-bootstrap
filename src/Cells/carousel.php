<div id="<?= esc($carouselId) ?>" class="<?= esc($carouselClasses) ?>"<?= $ride ? ' data-bs-ride="carousel"' : '' ?> data-bs-touch="<?= $touch ? 'true' : 'false' ?>" data-bs-wrap="<?= $wrap ? 'true' : 'false' ?>"<?= $interval !== '' ? ' data-bs-interval="' . esc($interval) . '"' : '' ?>>
    <?php if ($indicators && $items !== []): ?>
        <div class="carousel-indicators">
            <?php foreach ($items as $index => $item): ?>
                <button type="button" data-bs-target="#<?= esc($carouselId) ?>" data-bs-slide-to="<?= $index ?>"<?= $item['active'] ? ' class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="carousel-inner">
        <?php foreach ($items as $item): ?>
            <div class="carousel-item<?= $item['active'] ? ' active' : '' ?>"<?= $item['interval'] !== '' ? ' data-bs-interval="' . esc((string) $item['interval']) . '"' : '' ?>>
                <img src="<?= esc((string) $item['src']) ?>" class="d-block w-100" alt="<?= esc((string) $item['alt']) ?>">
                <?php if ((string) $item['titleHtml'] !== '' || (string) $item['captionHtml'] !== ''): ?>
                    <div class="<?= esc((string) $item['captionClasses']) ?>">
                        <?php if ((string) $item['titleHtml'] !== ''): ?>
                            <h5><?= (string) $item['titleHtml'] ?></h5>
                        <?php endif; ?>
                        <?php if ((string) $item['captionHtml'] !== ''): ?>
                            <p><?= (string) $item['captionHtml'] ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($controls && $items !== []): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#<?= esc($carouselId) ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#<?= esc($carouselId) ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    <?php endif; ?>
</div>
