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
use domProjects\CodeIgniterBootstrap\Cells\StatsCardsCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class StatsCardsCellTest extends CIUnitTestCase
{
    public function testStatsCardsRenderExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(StatsCardsCell::class, [
            'items' => [
                ['label' => 'Users', 'value' => '128'],
                ['label' => 'Revenue', 'value' => '$4.2k', 'description' => 'This month'],
            ],
        ]);

        $this->assertStringContainsString('class="row g-3"', $html);
        $this->assertStringContainsString('class="col-md-6 col-xl-3"', $html);
        $this->assertStringContainsString('class="card h-100 shadow-sm border-0"', $html);
        $this->assertStringContainsString('Users', $html);
        $this->assertStringContainsString('$4.2k', $html);
    }

    public function testStatsCardsSupportVariantsAndMeta(): void
    {
        $html = service('viewcell')->render(StatsCardsCell::class, [
            'items' => [
                ['label' => 'Growth', 'value' => '+12%', 'variant' => 'success', 'meta' => 'vs last month'],
            ],
        ]);

        $this->assertStringContainsString('class="card h-100 shadow-sm border-0 text-bg-success"', $html);
        $this->assertStringContainsString('vs last month', $html);
    }
}
