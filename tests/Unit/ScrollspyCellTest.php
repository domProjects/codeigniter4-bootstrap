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
use domProjects\CodeIgniterBootstrap\Cells\ScrollspyCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ScrollspyCellTest extends CIUnitTestCase
{
    public function testScrollspyRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ScrollspyCell::class, [
            'id'    => 'docsScrollspy',
            'items' => [
                ['label' => 'First', 'title' => 'First heading', 'content' => 'First body'],
                ['label' => 'Second', 'title' => 'Second heading', 'content' => 'Second body'],
            ],
        ]);

        $this->assertStringContainsString('<nav id="docsScrollspy" class="nav nav-pills flex-column mb-3">', $html);
        $this->assertStringContainsString('href="#docsScrollspy-section-1"', $html);
        $this->assertStringContainsString('data-bs-spy="scroll"', $html);
        $this->assertStringContainsString('data-bs-target="#docsScrollspy"', $html);
        $this->assertStringContainsString('style="height: 260px; overflow-y: auto;"', $html);
        $this->assertStringContainsString('<h4 id="docsScrollspy-section-1">First heading</h4>', $html);
    }

    public function testScrollspySupportsListGroupLayoutAndCustomHeight(): void
    {
        $html = service('viewcell')->render(ScrollspyCell::class, [
            'navType'        => 'list-group',
            'height'         => '320px',
            'contentClasses' => 'shadow-sm',
            'items'          => [
                ['id' => 'intro', 'label' => 'Intro', 'content' => 'Welcome'],
            ],
        ]);

        $this->assertStringContainsString('class="list-group mb-3"', $html);
        $this->assertStringContainsString('class="list-group-item list-group-item-action"', $html);
        $this->assertStringContainsString('style="height: 320px; overflow-y: auto;"', $html);
        $this->assertStringContainsString('class="scrollspy-example bg-body-tertiary p-3 rounded-2 shadow-sm"', $html);
        $this->assertStringContainsString('<h4 id="intro">Intro</h4>', $html);
    }
}
