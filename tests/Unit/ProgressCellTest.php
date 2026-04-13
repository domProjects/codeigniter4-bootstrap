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
use domProjects\CodeIgniterBootstrap\Cells\ProgressCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ProgressCellTest extends CIUnitTestCase
{
    public function testProgressRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ProgressCell::class, [
            'value' => 25,
            'label' => '25%',
        ]);

        $this->assertStringContainsString('class="progress"', $html);
        $this->assertStringContainsString('class="progress-bar"', $html);
        $this->assertStringContainsString('style="width: 25%;"', $html);
        $this->assertStringContainsString('>25%</div>', $html);
    }

    public function testProgressSupportsVariantsStripedAnimatedAndHeight(): void
    {
        $html = service('viewcell')->render(ProgressCell::class, [
            'value'    => 50,
            'variant'  => 'success',
            'striped'  => true,
            'animated' => true,
            'height'   => '1.5rem',
        ]);

        $this->assertStringContainsString('style="height: 1.5rem;"', $html);
        $this->assertStringContainsString('class="progress-bar text-bg-success progress-bar-striped progress-bar-animated"', $html);
    }

    public function testProgressSupportsMultipleBars(): void
    {
        $html = service('viewcell')->render(ProgressCell::class, [
            'bars' => [
                ['value' => 15, 'variant' => 'success'],
                ['value' => 30, 'variant' => 'info'],
            ],
        ]);

        $this->assertStringContainsString('text-bg-success', $html);
        $this->assertStringContainsString('text-bg-info', $html);
    }
}
