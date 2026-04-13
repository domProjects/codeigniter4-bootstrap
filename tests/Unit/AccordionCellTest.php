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
use domProjects\CodeIgniterBootstrap\Cells\AccordionCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AccordionCellTest extends CIUnitTestCase
{
    public function testAccordionRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(AccordionCell::class, [
            'items' => [
                ['title' => 'Item 1', 'content' => 'Body 1', 'active' => true],
                ['title' => 'Item 2', 'content' => 'Body 2'],
            ],
        ]);

        $this->assertStringContainsString('class="accordion"', $html);
        $this->assertStringContainsString('class="accordion-button"', $html);
        $this->assertStringContainsString('class="accordion-collapse collapse show"', $html);
        $this->assertStringContainsString('data-bs-parent="#accordion-', $html);
    }

    public function testAccordionSupportsFlushAndAlwaysOpen(): void
    {
        $html = service('viewcell')->render(AccordionCell::class, [
            'flush'      => true,
            'alwaysOpen' => true,
            'items'      => [
                ['title' => 'Item 1', 'content' => 'Body 1'],
            ],
        ]);

        $this->assertStringContainsString('class="accordion accordion-flush"', $html);
        $this->assertStringNotContainsString('data-bs-parent=', $html);
    }

    public function testAccordionContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(AccordionCell::class, [
            'items' => [
                ['title' => '<strong>Item</strong>', 'content' => '<em>Body</em>'],
            ],
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Item&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;em&gt;Body&lt;/em&gt;', $html);
    }
}
