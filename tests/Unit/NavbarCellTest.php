<?php

declare(strict_types=1);

/**
 * This file is part of domprojects/codeigniter4-bootstrap.
 *
 * (c) domProjects
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace domProjects\CodeIgniterBootstrap\Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use domProjects\CodeIgniterBootstrap\Cells\NavbarCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class NavbarCellTest extends CIUnitTestCase
{
    public function testNavbarRendersBrandAndNavItems(): void
    {
        $html = service('viewcell')->render(NavbarCell::class, [
            'brandLabel' => 'Demo',
            'brandUrl'   => '/',
            'items'      => [
                ['label' => 'Home', 'url' => '/', 'active' => true],
                ['label' => 'Features', 'url' => '/features'],
            ],
        ]);

        $this->assertStringContainsString('class="navbar navbar-expand-lg bg-body-tertiary"', $html);
        $this->assertStringContainsString('<a class="navbar-brand" href="/">Demo</a>', $html);
        $this->assertStringContainsString('class="nav-link active"', $html);
        $this->assertStringContainsString('href="/features"', $html);
    }

    public function testNavbarSupportsThemeContainerAndExtraContent(): void
    {
        $html = service('viewcell')->render(NavbarCell::class, [
            'brandLabel' => 'Demo',
            'theme'      => 'dark',
            'container'  => 'default',
            'content'    => '<span class="navbar-text">Signed in</span>',
            'escape'     => false,
        ]);

        $this->assertStringContainsString('data-bs-theme="dark"', $html);
        $this->assertStringContainsString('<div class="container">', $html);
        $this->assertStringContainsString('<span class="navbar-text">Signed in</span>', $html);
    }

    public function testLabelsAreEscapedByDefault(): void
    {
        $html = service('viewcell')->render(NavbarCell::class, [
            'brandLabel' => '<strong>Demo</strong>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Demo&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Demo</strong>', $html);
    }
}
