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
use domProjects\CodeIgniterBootstrap\Cells\DescriptionListCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class DescriptionListCellTest extends CIUnitTestCase
{
    public function testDescriptionListRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(DescriptionListCell::class, [
            'items' => [
                ['term' => 'Name', 'description' => 'Jane Doe'],
                ['term' => 'Role', 'description' => 'Admin'],
            ],
        ]);

        $this->assertStringContainsString('<dl class="row">', $html);
        $this->assertStringContainsString('<dt class="col-sm-3">Name</dt>', $html);
        $this->assertStringContainsString('<dd class="col-sm-9">Jane Doe</dd>', $html);
    }

    public function testDescriptionListSupportsCustomClasses(): void
    {
        $html = service('viewcell')->render(DescriptionListCell::class, [
            'classes'            => 'gy-2',
            'termClasses'        => 'col-4 fw-semibold',
            'descriptionClasses' => 'col-8',
            'items'              => [
                ['term' => 'Email', 'description' => 'jane@example.com'],
            ],
        ]);

        $this->assertStringContainsString('<dl class="row gy-2">', $html);
        $this->assertStringContainsString('<dt class="col-4 fw-semibold">Email</dt>', $html);
        $this->assertStringContainsString('<dd class="col-8">jane@example.com</dd>', $html);
    }
}
