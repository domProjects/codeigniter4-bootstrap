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
use domProjects\CodeIgniterBootstrap\Cells\CardCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CardCellTest extends CIUnitTestCase
{
    public function testSimpleCardRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(CardCell::class, [
            'title'   => 'Featured',
            'content' => 'This is a wider card with supporting text.',
        ]);

        $this->assertStringContainsString('class="card"', $html);
        $this->assertStringContainsString('<h5 class="card-title">Featured</h5>', $html);
        $this->assertStringContainsString('<p class="card-text">This is a wider card with supporting text.</p>', $html);
    }

    public function testCardSupportsHeaderFooterAndBottomImage(): void
    {
        $html = service('viewcell')->render(CardCell::class, [
            'header'        => 'Header',
            'title'         => 'Card title',
            'subtitle'      => 'Card subtitle',
            'content'       => 'Card body',
            'footer'        => 'Footer',
            'image'         => '/images/demo.png',
            'imageAlt'      => 'Demo image',
            'imagePosition' => 'bottom',
            'classes'       => 'shadow-sm',
            'bodyClasses'   => 'text-center',
        ]);

        $this->assertStringContainsString('class="card shadow-sm"', $html);
        $this->assertStringContainsString('<div class="card-header">Header</div>', $html);
        $this->assertStringContainsString('<h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>', $html);
        $this->assertStringContainsString('<div class="card-footer">Footer</div>', $html);
        $this->assertStringContainsString('class="card-img-bottom"', $html);
        $this->assertStringContainsString('alt="Demo image"', $html);
    }

    public function testCardContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(CardCell::class, [
            'content' => '<strong>Unsafe</strong>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Unsafe&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Unsafe</strong>', $html);
    }
}
