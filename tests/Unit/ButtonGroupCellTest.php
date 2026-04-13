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
use domProjects\CodeIgniterBootstrap\Cells\ButtonGroupCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ButtonGroupCellTest extends CIUnitTestCase
{
    public function testButtonGroupRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ButtonGroupCell::class, [
            'items' => [
                ['label' => 'Left'],
                ['label' => 'Middle', 'active' => true],
                ['label' => 'Right'],
            ],
        ]);

        $this->assertStringContainsString('class="btn-group"', $html);
        $this->assertStringContainsString('role="group"', $html);
        $this->assertStringContainsString('aria-label="Button group"', $html);
        $this->assertStringContainsString('class="btn btn-primary active"', $html);
        $this->assertStringContainsString('aria-pressed="true"', $html);
    }

    public function testButtonGroupSupportsVerticalOutlineAndAnchors(): void
    {
        $html = service('viewcell')->render(ButtonGroupCell::class, [
            'vertical' => true,
            'size'     => 'sm',
            'label'    => 'Actions',
            'items'    => [
                ['label' => 'Profile', 'href' => '/profile', 'type' => 'secondary', 'outline' => true],
                ['label' => 'Disabled', 'href' => '/disabled', 'disabled' => true],
            ],
        ]);

        $this->assertStringContainsString('class="btn-group-vertical btn-group-sm"', $html);
        $this->assertStringContainsString('aria-label="Actions"', $html);
        $this->assertStringContainsString('class="btn btn-outline-secondary"', $html);
        $this->assertStringContainsString('href="/profile"', $html);
        $this->assertStringContainsString('class="btn btn-primary disabled"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }
}
