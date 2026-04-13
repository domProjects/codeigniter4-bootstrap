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
use domProjects\CodeIgniterBootstrap\Cells\CarouselCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CarouselCellTest extends CIUnitTestCase
{
    public function testCarouselRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(CarouselCell::class, [
            'items' => [
                ['src' => '/img/slide-1.jpg', 'alt' => 'Slide 1', 'active' => true],
                ['src' => '/img/slide-2.jpg', 'alt' => 'Slide 2'],
            ],
        ]);

        $this->assertStringContainsString('class="carousel slide"', $html);
        $this->assertStringContainsString('class="carousel-indicators"', $html);
        $this->assertStringContainsString('class="carousel-item active"', $html);
        $this->assertStringContainsString('class="carousel-control-prev"', $html);
    }

    public function testCarouselSupportsFadeRideAndPerSlideInterval(): void
    {
        $html = service('viewcell')->render(CarouselCell::class, [
            'fade'     => true,
            'ride'     => true,
            'interval' => 7000,
            'items'    => [
                ['src' => '/img/slide-1.jpg', 'alt' => 'Slide 1', 'interval' => 10000],
            ],
        ]);

        $this->assertStringContainsString('class="carousel slide carousel-fade"', $html);
        $this->assertStringContainsString('data-bs-ride="carousel"', $html);
        $this->assertStringContainsString('data-bs-interval="7000"', $html);
        $this->assertStringContainsString('data-bs-interval="10000"', $html);
    }

    public function testCarouselCaptionsAreEscapedByDefault(): void
    {
        $html = service('viewcell')->render(CarouselCell::class, [
            'items' => [
                ['src' => '/img/slide-1.jpg', 'alt' => 'Slide 1', 'title' => '<strong>Title</strong>', 'caption' => '<em>Caption</em>'],
            ],
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Title&lt;/strong&gt;', $html);
        $this->assertStringContainsString('&lt;em&gt;Caption&lt;/em&gt;', $html);
    }
}
