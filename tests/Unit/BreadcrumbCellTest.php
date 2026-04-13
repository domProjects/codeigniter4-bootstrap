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
use domProjects\CodeIgniterBootstrap\Cells\BreadcrumbCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BreadcrumbCellTest extends CIUnitTestCase
{
    public function testBreadcrumbRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(BreadcrumbCell::class, [
            'items' => [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Library', 'url' => '/library'],
                ['label' => 'Data', 'active' => true],
            ],
        ]);

        $this->assertStringContainsString('<nav aria-label="breadcrumb">', $html);
        $this->assertStringContainsString('<ol class="breadcrumb">', $html);
        $this->assertStringContainsString('<a href="/">Home</a>', $html);
        $this->assertStringContainsString('<a href="/library">Library</a>', $html);
        $this->assertStringContainsString('class="breadcrumb-item active" aria-current="page"', $html);
        $this->assertStringContainsString('Data', $html);
    }

    public function testLastItemBecomesActiveByDefault(): void
    {
        $html = service('viewcell')->render(BreadcrumbCell::class, [
            'items' => [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Current'],
            ],
        ]);

        $this->assertStringContainsString('Current', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function testLabelsAreEscapedByDefault(): void
    {
        $html = service('viewcell')->render(BreadcrumbCell::class, [
            'items' => [
                ['label' => '<strong>Home</strong>', 'url' => '/'],
            ],
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Home&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Home</strong>', $html);
    }

    public function testBreadcrumbSupportsCustomDividerAndSharedClasses(): void
    {
        $html = service('viewcell')->render(BreadcrumbCell::class, [
            'divider'    => '>',
            'listClasses'=> 'mb-0',
            'itemClasses'=> 'text-uppercase',
            'linkClasses'=> 'link-body-emphasis',
            'items'      => [
                ['label' => 'Home', 'url' => '/', 'classes' => 'fw-semibold'],
                ['label' => 'Library', 'url' => '/library'],
                ['label' => 'Data', 'current' => 'location'],
            ],
        ]);

        $this->assertStringContainsString('<ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: &#039;&gt;&#039;;">', $html);
        $this->assertStringContainsString('class="breadcrumb-item text-uppercase fw-semibold"', $html);
        $this->assertStringContainsString('<a href="/" class="link-body-emphasis">Home</a>', $html);
        $this->assertStringContainsString('aria-current="location"', $html);
    }
}
