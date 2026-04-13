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
use domProjects\CodeIgniterBootstrap\Cells\AlertCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AlertCellTest extends CIUnitTestCase
{
    public function testCellCanBeRenderedThroughViewCellService(): void
    {
        $html = service('viewcell')->render(AlertCell::class, [
            'message' => 'Profile updated.',
            'variant' => 'success',
        ]);

        $this->assertStringContainsString('class="alert alert-success"', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('Profile updated.', $html);
    }

    public function testDismissibleAlertRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(AlertCell::class, [
            'content'      => 'Please review the highlighted fields.',
            'type'         => 'warning',
            'dismissible'  => 'true',
            'closeLabel'   => 'Dismiss alert',
            'classes'      => 'mt-3 shadow-sm',
        ]);

        $this->assertStringContainsString('class="alert alert-warning alert-dismissible fade show mt-3 shadow-sm"', $html);
        $this->assertStringContainsString('data-bs-dismiss="alert"', $html);
        $this->assertStringContainsString('class="btn-close"', $html);
        $this->assertStringContainsString('aria-label="Dismiss alert"', $html);
    }

    public function testContentIsEscapedByDefault(): void
    {
        $html = service('viewcell')->render(AlertCell::class, [
            'content' => '<strong>Saved</strong>',
        ]);

        $this->assertStringContainsString('&lt;strong&gt;Saved&lt;/strong&gt;', $html);
        $this->assertStringNotContainsString('<strong>Saved</strong>', $html);
    }

    public function testEscapeCanBeDisabledForHtmlContentAndHeading(): void
    {
        $html = service('viewcell')->render(AlertCell::class, [
            'heading' => 'Well done!',
            'content' => '<strong>Saved</strong>',
            'escape'  => false,
        ]);

        $this->assertStringContainsString('<h4 class="alert-heading">Well done!</h4>', $html);
        $this->assertStringContainsString('<strong>Saved</strong>', $html);
    }
}
