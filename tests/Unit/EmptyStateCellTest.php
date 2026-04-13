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
use domProjects\CodeIgniterBootstrap\Cells\EmptyStateCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class EmptyStateCellTest extends CIUnitTestCase
{
    public function testEmptyStateRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(EmptyStateCell::class, [
            'title'   => 'No projects',
            'content' => 'Create your first project to get started.',
        ]);

        $this->assertStringContainsString('class="text-center py-5"', $html);
        $this->assertStringContainsString('<h2 class="h3 mb-3">No projects</h2>', $html);
        $this->assertStringContainsString('Create your first project to get started.', $html);
    }

    public function testEmptyStateSupportsActions(): void
    {
        $html = service('viewcell')->render(EmptyStateCell::class, [
            'actions' => [
                ['label' => 'Create project', 'href' => '/projects/create'],
                ['label' => 'Learn more', 'variant' => 'outline-secondary'],
            ],
        ]);

        $this->assertStringContainsString('href="/projects/create"', $html);
        $this->assertStringContainsString('class="btn btn-primary"', $html);
        $this->assertStringContainsString('class="btn btn-outline-secondary"', $html);
    }
}
