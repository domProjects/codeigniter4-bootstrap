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
use domProjects\CodeIgniterBootstrap\Cells\ListGroupCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ListGroupCellTest extends CIUnitTestCase
{
    public function testSimpleListGroupRendersExpectedMarkup(): void
    {
        $html = service('viewcell')->render(ListGroupCell::class, [
            'items' => [
                ['label' => 'First item'],
                ['label' => 'Second item', 'active' => true],
            ],
        ]);

        $this->assertStringContainsString('<ul class="list-group">', $html);
        $this->assertStringContainsString('class="list-group-item active"', $html);
        $this->assertStringContainsString('Second item', $html);
    }

    public function testInteractiveListGroupSupportsLinksBadgesAndDisabledItems(): void
    {
        $html = service('viewcell')->render(ListGroupCell::class, [
            'items' => [
                ['label' => 'Inbox', 'url' => '/inbox', 'badge' => '14'],
                ['label' => 'Archive', 'disabled' => true],
            ],
        ]);

        $this->assertStringContainsString('<div class="list-group">', $html);
        $this->assertStringContainsString('class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"', $html);
        $this->assertStringContainsString('href="/inbox"', $html);
        $this->assertStringContainsString('class="badge text-bg-primary rounded-pill"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    public function testNumberedFlushHorizontalListGroupRendersExpectedClasses(): void
    {
        $html = service('viewcell')->render(ListGroupCell::class, [
            'numbered'   => true,
            'flush'      => true,
            'horizontal' => 'md',
            'items'      => [
                ['label' => 'One'],
            ],
        ]);

        $this->assertStringContainsString('class="list-group list-group-flush list-group-numbered list-group-horizontal-md"', $html);
        $this->assertStringContainsString('<ol', $html);
    }
}
