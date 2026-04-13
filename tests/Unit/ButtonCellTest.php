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
use domProjects\CodeIgniterBootstrap\Cells\ButtonCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ButtonCellTest extends CIUnitTestCase
{
    public function testDefaultButtonRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ButtonCell::class, [
            'message' => 'Save',
        ]);

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('class="btn btn-primary"', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('>Save</button>', $html);
    }

    public function testOutlineAnchorButtonRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ButtonCell::class, [
            'content' => 'View profile',
            'href'    => '/profile',
            'type'    => 'secondary',
            'outline' => true,
            'size'    => 'sm',
            'classes' => 'w-100',
        ]);

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('class="btn btn-outline-secondary btn-sm w-100"', $html);
        $this->assertStringContainsString('href="/profile"', $html);
        $this->assertStringContainsString('role="button"', $html);
    }

    public function testDisabledAnchorGetsAccessibilityAttributes(): void
    {
        $html = service('viewcell')->render(ButtonCell::class, [
            'content'  => 'Unavailable',
            'href'     => '/disabled',
            'disabled' => true,
        ]);

        $this->assertStringContainsString('class="btn btn-primary disabled"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
        $this->assertStringNotContainsString('href="/disabled"', $html);
    }
}
