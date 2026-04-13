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
use domProjects\CodeIgniterBootstrap\Cells\SpinnerCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class SpinnerCellTest extends CIUnitTestCase
{
    public function testSpinnerRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(SpinnerCell::class, []);

        $this->assertStringContainsString('class="spinner-border"', $html);
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringContainsString('class="visually-hidden">Loading...</span>', $html);
    }

    public function testSpinnerSupportsGrowVariantAndSmallSize(): void
    {
        $html = service('viewcell')->render(SpinnerCell::class, [
            'type'    => 'grow',
            'variant' => 'primary',
            'small'   => true,
            'label'   => 'Please wait',
        ]);

        $this->assertStringContainsString('class="spinner-grow text-primary spinner-grow-sm"', $html);
        $this->assertStringContainsString('Please wait', $html);
    }
}
