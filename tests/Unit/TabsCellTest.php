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
use domProjects\CodeIgniterBootstrap\Cells\TabsCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class TabsCellTest extends CIUnitTestCase
{
    public function testTabsRenderExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(TabsCell::class, [
            'items' => [
                ['label' => 'Home', 'content' => 'Home content', 'active' => true],
                ['label' => 'Profile', 'content' => 'Profile content'],
            ],
        ]);

        $this->assertStringContainsString('class="nav nav-tabs"', $html);
        $this->assertStringContainsString('data-bs-toggle="tab"', $html);
        $this->assertStringContainsString('class="nav-link active"', $html);
        $this->assertStringContainsString('class="tab-pane active"', $html);
    }

    public function testTabsSupportPillsFillJustifiedAndFade(): void
    {
        $html = service('viewcell')->render(TabsCell::class, [
            'pills'     => true,
            'fill'      => true,
            'justified' => true,
            'fade'      => true,
            'items'     => [
                ['label' => 'Home', 'content' => 'Home', 'active' => true],
            ],
        ]);

        $this->assertStringContainsString('class="nav nav-pills nav-fill nav-justified"', $html);
        $this->assertStringContainsString('class="tab-pane fade show active"', $html);
    }

    public function testTabLabelsAndContentAreEscapedByDefault(): void
    {
        $html = service('viewcell')->render(TabsCell::class, [
            'items' => [
                ['label' => '<strong>Home</strong>', 'content' => '<em>Body</em>'],
            ],
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Home&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;em&gt;Body&lt;/em&gt;', $html);
    }
}
