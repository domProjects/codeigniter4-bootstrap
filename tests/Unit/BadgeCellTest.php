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
use domProjects\CodeIgniterBootstrap\Cells\BadgeCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BadgeCellTest extends CIUnitTestCase
{
    public function testCellCanBeRenderedThroughViewCellService(): void
    {
        $html = service('viewcell')->render(BadgeCell::class, [
            'message' => 'New',
        ]);

        $this->assertStringContainsString('class="badge text-bg-secondary"', $html);
        $this->assertStringContainsString('New', $html);
    }

    public function testPillBadgeRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(BadgeCell::class, [
            'content' => '99+',
            'type'    => 'danger',
            'pill'    => 'true',
            'classes' => 'position-absolute top-0 start-100 translate-middle',
        ]);

        $this->assertStringContainsString(
            'class="badge text-bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"',
            $html,
        );
        $this->assertStringContainsString('99+', $html);
    }

    public function testHiddenTextCanBeRenderedForAccessibility(): void
    {
        $html = service('viewcell')->render(BadgeCell::class, [
            'content'    => '4',
            'hiddenText' => 'unread notifications',
        ]);

        $this->assertStringContainsString('<span class="visually-hidden">unread notifications</span>', $html);
    }

    public function testContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(BadgeCell::class, [
            'content' => '<strong>New</strong>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;New&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>New</strong>', $html);
    }

    public function testEscapeCanBeDisabledForTrustedHtmlContent(): void
    {
        $html = service('viewcell')->render(BadgeCell::class, [
            'content' => '<strong>New</strong>',
            'escape'  => false,
        ]);

        $this->assertStringContainsString('<strong>New</strong>', $html);
    }
}
