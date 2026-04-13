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
use domProjects\CodeIgniterBootstrap\Cells\ToastCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ToastCellTest extends CIUnitTestCase
{
    public function testToastRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ToastCell::class, [
            'title'    => 'Notification',
            'subtitle' => 'just now',
            'content'  => 'Hello world',
        ]);

        $this->assertStringContainsString('class="toast fade"', $html);
        $this->assertStringContainsString('class="toast-header"', $html);
        $this->assertStringContainsString('<strong class="me-auto">Notification</strong>', $html);
        $this->assertStringContainsString('<div class="toast-body">Hello world</div>', $html);
    }

    public function testToastSupportsShowDelayAndAutohideConfiguration(): void
    {
        $html = service('viewcell')->render(ToastCell::class, [
            'show'     => true,
            'autoHide' => false,
            'delay'    => 10000,
            'content'  => 'Body',
        ]);

        $this->assertStringContainsString('class="toast fade show"', $html);
        $this->assertStringContainsString('data-bs-autohide="false"', $html);
        $this->assertStringContainsString('data-bs-delay="10000"', $html);
    }

    public function testToastContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(ToastCell::class, [
            'title'   => '<strong>Title</strong>',
            'content' => '<em>Body</em>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Title&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;em&gt;Body&lt;/em&gt;', $html);
    }
}
