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
use domProjects\CodeIgniterBootstrap\Cells\CollapseCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CollapseCellTest extends CIUnitTestCase
{
    public function testCollapseRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(CollapseCell::class, [
            'content' => 'Collapsed content',
        ]);

        $this->assertStringContainsString('class="collapse"', $html);
        $this->assertStringContainsString('Collapsed content', $html);
    }

    public function testCollapseSupportsShowHorizontalAndCardBody(): void
    {
        $html = service('viewcell')->render(CollapseCell::class, [
            'content'    => 'Body',
            'show'       => true,
            'horizontal' => true,
            'card'       => true,
        ]);

        $this->assertStringContainsString('class="collapse collapse-horizontal show"', $html);
        $this->assertStringContainsString('class="card card-body"', $html);
    }

    public function testCollapseContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(CollapseCell::class, [
            'content' => '<strong>Body</strong>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Body&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Body</strong>', $html);
    }
}
