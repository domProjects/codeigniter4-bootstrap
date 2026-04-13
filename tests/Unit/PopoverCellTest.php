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
use domProjects\CodeIgniterBootstrap\Cells\PopoverCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class PopoverCellTest extends CIUnitTestCase
{
    public function testPopoverRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(PopoverCell::class, [
            'content' => 'Open popover',
            'title'   => 'Popover title',
            'body'    => 'Popover body',
        ]);

        $this->assertStringContainsString('data-bs-toggle="popover"', $html);
        $this->assertStringContainsString('title="Popover title"', $html);
        $this->assertStringContainsString('data-bs-content="Popover body"', $html);
        $this->assertStringContainsString('class="btn btn-secondary"', $html);
    }

    public function testPopoverSupportsPlacementOutlineAndAnchorMode(): void
    {
        $html = service('viewcell')->render(PopoverCell::class, [
            'content'   => 'More info',
            'body'      => 'Popover body',
            'placement' => 'bottom',
            'outline'   => true,
            'variant'   => 'danger',
            'href'      => '/help',
        ]);

        $this->assertStringContainsString('data-bs-placement="bottom"', $html);
        $this->assertStringContainsString('class="btn btn-outline-danger"', $html);
        $this->assertStringContainsString('href="/help"', $html);
        $this->assertStringContainsString('role="button"', $html);
    }

    public function testPopoverEscapesTitleAndBodyByDefault(): void
    {
        $html = service('viewcell')->render(PopoverCell::class, [
            'content' => 'Trigger',
            'title'   => '<strong>Title</strong>',
            'body'    => '<em>Body</em>',
        ]);

        $this->assertStringContainsString('&amp;lt;strong&amp;gt;Title&amp;lt;/strong&amp;gt;', $html);
        $this->assertStringContainsString('&amp;lt;em&amp;gt;Body&amp;lt;/em&amp;gt;', $html);
    }
}
