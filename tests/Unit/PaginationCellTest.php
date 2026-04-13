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
use domProjects\CodeIgniterBootstrap\Cells\PaginationCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class PaginationCellTest extends CIUnitTestCase
{
    public function testPaginationRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(PaginationCell::class, [
            'items' => [
                ['label' => 'Previous', 'disabled' => true],
                ['label' => '1', 'url' => '/page/1'],
                ['label' => '2', 'active' => true],
            ],
        ]);

        $this->assertStringContainsString('<nav aria-label="Pagination navigation">', $html);
        $this->assertStringContainsString('class="pagination"', $html);
        $this->assertStringContainsString('class="page-item disabled"', $html);
        $this->assertStringContainsString('href="/page/1"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function testPaginationSupportsSizeAndAlignment(): void
    {
        $html = service('viewcell')->render(PaginationCell::class, [
            'size'  => 'sm',
            'align' => 'center',
            'items' => [
                ['label' => '1', 'active' => true],
            ],
        ]);

        $this->assertStringContainsString('class="pagination pagination-sm justify-content-center"', $html);
    }

    public function testLabelsAreEscapedByDefault(): void
    {
        $html = service('viewcell')->render(PaginationCell::class, [
            'items' => [
                ['label' => '<strong>1</strong>'],
            ],
        ]);

        $this->assertStringContainsString('&lt;strong&gt;1&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>1</strong>', $html);
    }

    public function testPaginationSupportsGeneratedPagesEllipsisAndNavigationControls(): void
    {
        $html = service('viewcell')->render(PaginationCell::class, [
            'currentPage'      => 5,
            'totalPages'       => 10,
            'urlPattern'       => '/page/{page}',
            'showFirstLast'    => true,
            'showPreviousNext' => true,
            'window'           => 1,
            'align'            => 'end',
            'itemClasses'      => 'shadow-sm',
            'linkClasses'      => 'rounded-2',
        ]);

        $this->assertStringContainsString('class="pagination justify-content-end"', $html);
        $this->assertStringContainsString('aria-label="First"', $html);
        $this->assertStringContainsString('href="/page/1"', $html);
        $this->assertStringContainsString('&hellip;', $html);
        $this->assertStringContainsString('href="/page/4"', $html);
        $this->assertStringContainsString('class="page-item active shadow-sm"', $html);
        $this->assertStringContainsString('class="page-link rounded-2"', $html);
        $this->assertStringContainsString('aria-label="Next"', $html);
        $this->assertStringContainsString('href="/page/10"', $html);
    }

    public function testPaginationSupportsManualEllipsisAndCustomLinkClasses(): void
    {
        $html = service('viewcell')->render(PaginationCell::class, [
            'items' => [
                ['label' => 'Previous', 'disabled' => true, 'linkClasses' => 'px-3'],
                ['label' => '1', 'url' => '/page/1'],
                ['ellipsis' => true],
                ['label' => '10', 'url' => '/page/10'],
            ],
        ]);

        $this->assertStringContainsString('class="page-link px-3"', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('&hellip;', $html);
    }
}
