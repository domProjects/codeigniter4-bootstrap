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
use domProjects\CodeIgniterBootstrap\Cells\TooltipCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class TooltipCellTest extends CIUnitTestCase
{
    public function testTooltipRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(TooltipCell::class, [
            'content' => 'Hover me',
            'title'   => 'Tooltip text',
        ]);

        $this->assertStringContainsString('data-bs-toggle="tooltip"', $html);
        $this->assertStringContainsString('title="Tooltip text"', $html);
        $this->assertStringContainsString('class="btn btn-secondary"', $html);
    }

    public function testTooltipSupportsPlacementOutlineAndAnchorMode(): void
    {
        $html = service('viewcell')->render(TooltipCell::class, [
            'content'   => 'Help',
            'title'     => 'Tooltip text',
            'placement' => 'top',
            'outline'   => true,
            'variant'   => 'success',
            'href'      => '/help',
        ]);

        $this->assertStringContainsString('data-bs-placement="top"', $html);
        $this->assertStringContainsString('class="btn btn-outline-success"', $html);
        $this->assertStringContainsString('href="/help"', $html);
        $this->assertStringContainsString('role="button"', $html);
    }

    public function testTooltipEscapesTitleByDefault(): void
    {
        $html = service('viewcell')->render(TooltipCell::class, [
            'content' => 'Trigger',
            'title'   => '<strong>Tooltip</strong>',
        ]);

        $this->assertStringContainsString('&amp;lt;strong&amp;gt;Tooltip&amp;lt;/strong&amp;gt;', $html);
    }
}
