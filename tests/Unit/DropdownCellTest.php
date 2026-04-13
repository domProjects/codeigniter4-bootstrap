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
use domProjects\CodeIgniterBootstrap\Cells\DropdownCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class DropdownCellTest extends CIUnitTestCase
{
    public function testDropdownRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(DropdownCell::class, [
            'content' => 'Actions',
            'items'   => [
                ['label' => 'Profile', 'url' => '/profile'],
                ['label' => 'Settings'],
            ],
        ]);

        $this->assertStringContainsString('class="dropdown"', $html);
        $this->assertStringContainsString('class="btn btn-secondary dropdown-toggle"', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);
        $this->assertStringContainsString('href="/profile"', $html);
        $this->assertStringContainsString('class="dropdown-item"', $html);
    }

    public function testDropdownSupportsDirectionAlignmentAndSpecialItems(): void
    {
        $html = service('viewcell')->render(DropdownCell::class, [
            'content'   => 'Menu',
            'direction' => 'dropend',
            'align'     => 'end',
            'dark'      => true,
            'items'     => [
                ['header' => 'Account'],
                ['label' => 'Billing', 'disabled' => true],
                ['divider' => true],
                ['text' => 'Signed in as demo@example.com'],
            ],
        ]);

        $this->assertStringContainsString('class="dropdown dropend"', $html);
        $this->assertStringContainsString('class="dropdown-menu dropdown-menu-end dropdown-menu-dark"', $html);
        $this->assertStringContainsString('<h6 class="dropdown-header">Account</h6>', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('class="dropdown-item-text"', $html);
    }

    public function testLabelsAreEscapedByDefault(): void
    {
        $html = service('viewcell')->render(DropdownCell::class, [
            'content' => '<strong>Menu</strong>',
            'items'   => [
                ['label' => '<strong>Profile</strong>'],
            ],
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Menu&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;strong&gt;Profile&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Profile</strong>', $html);
    }
}
