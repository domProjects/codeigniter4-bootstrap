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
use domProjects\CodeIgniterBootstrap\Cells\OffcanvasCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OffcanvasCellTest extends CIUnitTestCase
{
    public function testOffcanvasRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(OffcanvasCell::class, [
            'title'   => 'Menu',
            'content' => 'Sidebar content',
        ]);

        $this->assertStringContainsString('class="offcanvas offcanvas-start"', $html);
        $this->assertStringContainsString('class="offcanvas-title"', $html);
        $this->assertStringContainsString('class="offcanvas-body"', $html);
    }

    public function testOffcanvasSupportsPlacementResponsiveAndBehaviorFlags(): void
    {
        $html = service('viewcell')->render(OffcanvasCell::class, [
            'placement'  => 'end',
            'responsive' => 'lg',
            'scroll'     => true,
            'backdrop'   => 'static',
            'theme'      => 'dark',
            'content'    => 'Body',
        ]);

        $this->assertStringContainsString('class="offcanvas-lg offcanvas-end"', $html);
        $this->assertStringContainsString('data-bs-scroll="true"', $html);
        $this->assertStringContainsString('data-bs-backdrop="static"', $html);
        $this->assertStringContainsString('data-bs-theme="dark"', $html);
    }

    public function testOffcanvasContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(OffcanvasCell::class, [
            'title'   => '<strong>Menu</strong>',
            'content' => '<em>Body</em>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Menu&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;em&gt;Body&lt;/em&gt;', $html);
    }
}
