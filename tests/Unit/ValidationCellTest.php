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
use domProjects\CodeIgniterBootstrap\Cells\ValidationCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ValidationCellTest extends CIUnitTestCase
{
    public function testValidationCellRendersInvalidFeedbackByDefault(): void
    {
        $html = service('viewcell')->render(ValidationCell::class, [
            'message' => 'Please provide a valid city.',
        ]);

        $this->assertStringContainsString('class="invalid-feedback"', $html);
        $this->assertStringContainsString('Please provide a valid city.', $html);
    }

    public function testValidationCellSupportsValidTooltipAndCustomClasses(): void
    {
        $html = service('viewcell')->render(ValidationCell::class, [
            'content' => 'Looks good!',
            'state'   => 'valid',
            'tooltip' => true,
            'classes' => 'd-block',
            'id'      => 'cityFeedback',
        ]);

        $this->assertStringContainsString('class="valid-tooltip d-block"', $html);
        $this->assertStringContainsString('id="cityFeedback"', $html);
        $this->assertStringContainsString('Looks good!', $html);
    }
}
