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
use domProjects\CodeIgniterBootstrap\Cells\ButtonToolbarCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ButtonToolbarCellTest extends CIUnitTestCase
{
    public function testButtonToolbarRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(ButtonToolbarCell::class, [
            'groups' => [
                [
                    'label' => 'First group',
                    'items' => [
                        ['label' => '1'],
                        ['label' => '2'],
                    ],
                ],
                [
                    'label' => 'Second group',
                    'items' => [
                        ['label' => '3'],
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString('class="btn-toolbar"', $html);
        $this->assertStringContainsString('role="toolbar"', $html);
        $this->assertStringContainsString('aria-label="Button toolbar"', $html);
        $this->assertStringContainsString('aria-label="First group"', $html);
        $this->assertStringContainsString('class="btn btn-primary"', $html);
    }

    public function testButtonToolbarSupportsVerticalGroupsAndAnchors(): void
    {
        $html = service('viewcell')->render(ButtonToolbarCell::class, [
            'classes' => 'justify-content-between',
            'groups'  => [
                [
                    'vertical' => true,
                    'size'     => 'sm',
                    'items'    => [
                        ['label' => 'Profile', 'href' => '/profile', 'type' => 'secondary', 'outline' => true],
                        ['label' => 'Delete', 'disabled' => true],
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString('class="btn-toolbar justify-content-between"', $html);
        $this->assertStringContainsString('class="btn-group-vertical btn-group-sm"', $html);
        $this->assertStringContainsString('class="btn btn-outline-secondary"', $html);
        $this->assertStringContainsString('href="/profile"', $html);
        $this->assertStringContainsString('disabled="disabled"', $html);
    }
}
