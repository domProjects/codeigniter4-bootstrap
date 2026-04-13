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
use domProjects\CodeIgniterBootstrap\Cells\ImageCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ImageCellTest extends CIUnitTestCase
{
    public function testImageRendersResponsiveBootstrapMarkupByDefault(): void
    {
        $html = service('viewcell')->render(ImageCell::class, [
            'src' => '/images/demo.png',
            'alt' => 'Demo image',
        ]);

        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('src="/images/demo.png"', $html);
        $this->assertStringContainsString('alt="Demo image"', $html);
        $this->assertStringContainsString('class="img-fluid"', $html);
    }

    public function testImageSupportsThumbnailAlignmentAndPictureSources(): void
    {
        $html = service('viewcell')->render(ImageCell::class, [
            'src'       => '/images/demo.png',
            'alt'       => 'Demo image',
            'thumbnail' => true,
            'rounded'   => true,
            'align'     => 'center',
            'classes'   => 'shadow-sm',
            'attrs'     => [
                'loading' => 'lazy',
                'width'   => 320,
            ],
            'sources'   => [
                ['srcset' => '/images/demo.webp', 'type' => 'image/webp'],
            ],
        ]);

        $this->assertStringContainsString('<picture>', $html);
        $this->assertStringContainsString('<source srcset="/images/demo.webp" type="image/webp">', $html);
        $this->assertStringContainsString('class="img-fluid img-thumbnail rounded mx-auto d-block shadow-sm"', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
        $this->assertStringContainsString('width="320"', $html);
    }
}
