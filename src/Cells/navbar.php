<nav class="<?= esc($navbarClasses) ?>"<?= $theme !== '' ? ' data-bs-theme="' . esc($theme) . '"' : '' ?>>
    <div<?= $containerClass !== '' ? ' class="' . esc($containerClass) . '"' : '' ?>>
        <?php if ($brandLabelHtml !== ''): ?>
            <a class="navbar-brand" href="<?= esc($brandUrl === '' ? '#' : $brandUrl) ?>"><?= $brandLabelHtml ?></a>
        <?php endif; ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#<?= esc($collapseId) ?>" aria-controls="<?= esc($collapseId) ?>" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="<?= esc($collapseId) ?>">
            <?php if ($items !== []): ?>
                <ul class="navbar-nav <?= esc($navClasses) ?>">
                    <?php foreach ($items as $item): ?>
                        <?php
                        $linkClasses = 'nav-link';

                        if ($item['active']) {
                            $linkClasses .= ' active';
                        }

                        if ($item['disabled']) {
                            $linkClasses .= ' disabled';
                        }

                        if ($item['classes'] !== '') {
                            $linkClasses .= ' ' . $item['classes'];
                        }
                        ?>
                        <li class="nav-item">
                            <a class="<?= esc(trim($linkClasses)) ?>" href="<?= esc($item['disabled'] || $item['url'] === '' ? '#' : $item['url']) ?>"<?= $item['active'] ? ' aria-current="page"' : '' ?><?= $item['disabled'] ? ' aria-disabled="true" tabindex="-1"' : '' ?>><?= $item['labelHtml'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($contentHtml !== ''): ?>
                <?= $contentHtml ?>
            <?php endif; ?>
        </div>
    </div>
</nav>
