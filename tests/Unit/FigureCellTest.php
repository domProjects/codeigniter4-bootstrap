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
use domProjects\CodeIgniterBootstrap\Cells\FigureCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FigureCellTest extends CIUnitTestCase
{
    public function testFigureRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(FigureCell::class, [
            'src'     => '/images/demo.png',
            'alt'     => 'Demo image',
            'caption' => 'A caption for the above image.',
            'rounded' => true,
        ]);

        $this->assertStringContainsString('<figure class="figure">', $html);
        $this->assertStringContainsString('class="figure-img img-fluid rounded"', $html);
        $this->assertStringContainsString('<figcaption class="figure-caption">A caption for the above image.</figcaption>', $html);
    }

    public function testFigureSupportsCaptionAlignmentCustomClassesAndEscapedContent(): void
    {
        $html = service('viewcell')->render(FigureCell::class, [
            'image'         => '/images/demo.png',
            'imageAlt'      => 'Demo image',
            'content'       => '<strong>Unsafe</strong>',
            'captionAlign'  => 'end',
            'classes'       => 'mb-0',
            'imageClasses'  => 'shadow-sm',
            'captionClasses'=> 'small',
            'thumbnail'     => true,
            'sources'       => [
                ['srcset' => '/images/demo.webp', 'type' => 'image/webp'],
            ],
        ]);

        $this->assertStringContainsString('<figure class="figure mb-0">', $html);
        $this->assertStringContainsString('<picture>', $html);
        $this->assertStringContainsString('class="figure-img img-fluid img-thumbnail shadow-sm"', $html);
        $this->assertStringContainsString('class="figure-caption text-end small"', $html);
        $this->assertStringContainsString('&lt;strong&gt;Unsafe&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Unsafe</strong>', $html);
    }
}
