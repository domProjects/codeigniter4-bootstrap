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
use domProjects\CodeIgniterBootstrap\Cells\PlaceholderCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class PlaceholderCellTest extends CIUnitTestCase
{
    public function testPlaceholderRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(PlaceholderCell::class, [
            'width' => 6,
        ]);

        $this->assertStringContainsString('class="placeholder d-block col-6"', $html);
    }

    public function testPlaceholderSupportsAnimationVariantAndMultipleItems(): void
    {
        $html = service('viewcell')->render(PlaceholderCell::class, [
            'animation' => 'glow',
            'items'     => [
                ['width' => 8, 'variant' => 'primary'],
                ['width' => 4, 'size' => 'sm'],
            ],
        ]);

        $this->assertStringContainsString('class="placeholder-glow"', $html);
        $this->assertStringContainsString('class="placeholder d-block col-8 bg-primary"', $html);
        $this->assertStringContainsString('class="placeholder d-block col-4 placeholder-sm"', $html);
    }
}
