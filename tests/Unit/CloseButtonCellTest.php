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
use domProjects\CodeIgniterBootstrap\Cells\CloseButtonCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CloseButtonCellTest extends CIUnitTestCase
{
    public function testCloseButtonRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(CloseButtonCell::class, []);

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('class="btn-close"', $html);
        $this->assertStringContainsString('aria-label="Close"', $html);
    }

    public function testCloseButtonSupportsDismissTargetAndDisabledState(): void
    {
        $html = service('viewcell')->render(CloseButtonCell::class, [
            'dismiss'  => 'modal',
            'target'   => '#demoModal',
            'theme'    => 'dark',
            'disabled' => true,
            'classes'  => 'ms-auto',
        ]);

        $this->assertStringContainsString('class="btn-close ms-auto"', $html);
        $this->assertStringContainsString('data-bs-dismiss="modal"', $html);
        $this->assertStringContainsString('data-bs-target="#demoModal"', $html);
        $this->assertStringContainsString('data-bs-theme="dark"', $html);
        $this->assertStringContainsString('disabled="disabled"', $html);
    }
}
