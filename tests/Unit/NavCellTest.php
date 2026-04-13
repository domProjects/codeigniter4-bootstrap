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
use domProjects\CodeIgniterBootstrap\Cells\NavCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class NavCellTest extends CIUnitTestCase
{
    public function testNavRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(NavCell::class, [
            'items' => [
                ['label' => 'Active', 'url' => '/active', 'active' => true],
                ['label' => 'Link', 'url' => '/link'],
            ],
        ]);

        $this->assertStringContainsString('class="nav nav-tabs"', $html);
        $this->assertStringContainsString('class="nav-link active"', $html);
        $this->assertStringContainsString('href="/active"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function testNavSupportsPillsFillVerticalAndDisabledLinks(): void
    {
        $html = service('viewcell')->render(NavCell::class, [
            'variant'   => 'pills',
            'fill'      => true,
            'vertical'  => true,
            'items'     => [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Disabled', 'disabled' => true],
            ],
        ]);

        $this->assertStringContainsString('class="nav nav-pills nav-fill flex-column"', $html);
        $this->assertStringContainsString('class="nav-link disabled"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringNotContainsString('href="#"', $html);
    }

    public function testNavSupportsTabbedContentAndFade(): void
    {
        $html = service('viewcell')->render(NavCell::class, [
            'variant' => 'underline',
            'fade'    => true,
            'items'   => [
                ['label' => 'Profile', 'content' => 'Profile content', 'active' => true],
                ['label' => 'Contact', 'content' => 'Contact content'],
            ],
        ]);

        $this->assertStringContainsString('class="nav nav-underline"', $html);
        $this->assertStringContainsString('data-bs-toggle="tab"', $html);
        $this->assertStringContainsString('class="tab-pane fade show active"', $html);
        $this->assertStringContainsString('Profile content', $html);
    }
}
