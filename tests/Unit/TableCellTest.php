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
use domProjects\CodeIgniterBootstrap\Cells\TableCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class TableCellTest extends CIUnitTestCase
{
    public function testTableRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(TableCell::class, [
            'headers' => ['#', 'Name', 'Email'],
            'rows'    => [
                [
                    ['tag' => 'th', 'content' => '1'],
                    'Jane Doe',
                    'jane@example.com',
                ],
            ],
        ]);

        $this->assertStringContainsString('class="table"', $html);
        $this->assertStringContainsString('<th scope="col">Name</th>', $html);
        $this->assertStringContainsString('<th scope="row">1</th>', $html);
        $this->assertStringContainsString('<td>jane@example.com</td>', $html);
    }

    public function testTableSupportsVariantsCaptionAndResponsiveWrapper(): void
    {
        $html = service('viewcell')->render(TableCell::class, [
            'variant'              => 'dark',
            'striped'              => true,
            'hover'                => true,
            'small'                => true,
            'responsive'           => true,
            'responsiveBreakpoint' => 'md',
            'caption'              => 'User summary',
            'captionTop'           => true,
            'headVariant'          => 'light',
            'headers'              => ['Name'],
            'rows'                 => [['Jane Doe']],
        ]);

        $this->assertStringContainsString('class="table-responsive-md"', $html);
        $this->assertStringContainsString('class="table table-dark table-striped table-hover table-sm"', $html);
        $this->assertStringContainsString('<caption class="caption-top">User summary</caption>', $html);
        $this->assertStringContainsString('<thead class="table-light">', $html);
    }

    public function testTableSupportsRowActionsAndEmptyState(): void
    {
        $html = service('viewcell')->render(TableCell::class, [
            'headers' => ['Name', 'Email'],
            'rows'    => [
                [
                    'classes' => 'align-middle',
                    'cells'   => ['Jane Doe', 'jane@example.com'],
                    'actions' => [
                        ['label' => 'Edit', 'href' => '/users/1/edit'],
                        ['label' => 'Delete', 'variant' => 'danger'],
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString('<th scope="col" class="text-nowrap">Actions</th>', $html);
        $this->assertStringContainsString('<tr class="align-middle">', $html);
        $this->assertStringContainsString('href="/users/1/edit"', $html);
        $this->assertStringContainsString('class="btn btn-outline-danger btn-sm"', $html);

        $emptyHtml = service('viewcell')->render(TableCell::class, [
            'headers'      => ['Name'],
            'emptyMessage' => 'No users found.',
        ]);

        $this->assertStringContainsString('<td colspan="1" class="text-center text-body-secondary">No users found.</td>', $emptyHtml);
    }

    public function testTableSupportsResponsiveStackHooks(): void
    {
        $html = service('viewcell')->render(TableCell::class, [
            'stacked'           => true,
            'stackedBreakpoint' => 'md',
            'headers'           => ['Name', 'Email'],
            'rows'              => [
                ['Jane Doe', 'jane@example.com'],
            ],
        ]);

        $this->assertStringContainsString('class="table table-stack"', $html);
        $this->assertStringContainsString('class="table-stack-responsive-md"', $html);
        $this->assertStringContainsString('data-label="Name"', $html);
        $this->assertStringContainsString('data-label="Email"', $html);
    }
}
