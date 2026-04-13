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
use domProjects\CodeIgniterBootstrap\Cells\ModalCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ModalCellTest extends CIUnitTestCase
{
    public function testModalRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ModalCell::class, [
            'title'   => 'Confirm delete',
            'content' => 'Are you sure?',
            'footer'  => '<button class="btn btn-danger">Delete</button>',
            'escape'  => false,
        ]);

        $this->assertStringContainsString('class="modal fade"', $html);
        $this->assertStringContainsString('class="modal-dialog"', $html);
        $this->assertStringContainsString('<h1 class="modal-title fs-5">Confirm delete</h1>', $html);
        $this->assertStringContainsString('<div class="modal-footer"><button class="btn btn-danger">Delete</button></div>', $html);
    }

    public function testModalSupportsSizingCenteredScrollableAndStaticBackdrop(): void
    {
        $html = service('viewcell')->render(ModalCell::class, [
            'size'           => 'lg',
            'centered'       => true,
            'scrollable'     => true,
            'staticBackdrop' => true,
            'keyboard'       => false,
            'content'        => 'Body',
        ]);

        $this->assertStringContainsString('class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"', $html);
        $this->assertStringContainsString('data-bs-backdrop="static"', $html);
        $this->assertStringContainsString('data-bs-keyboard="false"', $html);
    }

    public function testModalContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(ModalCell::class, [
            'title'   => '<strong>Title</strong>',
            'content' => '<em>Body</em>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Title&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;em&gt;Body&lt;/em&gt;', $html);
    }
}
