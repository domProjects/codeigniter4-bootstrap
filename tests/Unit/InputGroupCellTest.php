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
use domProjects\CodeIgniterBootstrap\Cells\InputGroupCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class InputGroupCellTest extends CIUnitTestCase
{
    public function testInputGroupRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(InputGroupCell::class, [
            'items' => [
                ['type' => 'text', 'content' => '@'],
                ['type' => 'input', 'name' => 'username', 'placeholder' => 'Username', 'ariaLabel' => 'Username'],
            ],
        ]);

        $this->assertStringContainsString('class="input-group"', $html);
        $this->assertStringContainsString('class="input-group-text"', $html);
        $this->assertStringContainsString('>@</span>', $html);
        $this->assertStringContainsString('class="form-control"', $html);
        $this->assertStringContainsString('name="username"', $html);
    }

    public function testInputGroupSupportsButtonsCheckboxAndSelect(): void
    {
        $html = service('viewcell')->render(InputGroupCell::class, [
            'size' => 'sm',
            'items' => [
                ['type' => 'button', 'label' => 'Go', 'variant' => 'outline-secondary', 'id' => 'button-addon1'],
                ['type' => 'checkbox', 'ariaLabel' => 'Checkbox'],
                ['type' => 'select', 'name' => 'choice', 'options' => [
                    ['label' => 'Choose...', 'selected' => true],
                    ['label' => 'One', 'value' => '1'],
                ]],
            ],
        ]);

        $this->assertStringContainsString('class="input-group input-group-sm"', $html);
        $this->assertStringContainsString('class="btn btn-outline-secondary"', $html);
        $this->assertStringContainsString('id="button-addon1"', $html);
        $this->assertStringContainsString('class="form-check-input mt-0"', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('class="form-select"', $html);
        $this->assertStringContainsString('selected="selected"', $html);
    }

    public function testInputGroupSupportsFloatingControls(): void
    {
        $html = service('viewcell')->render(InputGroupCell::class, [
            'items' => [
                ['type' => 'text', 'content' => '@'],
                [
                    'type'     => 'input',
                    'id'       => 'floatingUsername',
                    'name'     => 'username',
                    'label'    => 'Username',
                    'floating' => true,
                ],
                [
                    'type'     => 'select',
                    'id'       => 'floatingType',
                    'label'    => 'Type',
                    'floating' => true,
                    'options'  => [
                        ['label' => 'Choose type'],
                        ['label' => 'Admin'],
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString('class="form-floating"', $html);
        $this->assertStringContainsString('<input class="form-control" type="text" id="floatingUsername" name="username" placeholder=" ">', $html);
        $this->assertStringContainsString('<label for="floatingUsername">Username</label>', $html);
        $this->assertStringContainsString('<select class="form-select" id="floatingType">', $html);
        $this->assertStringContainsString('<label for="floatingType">Type</label>', $html);
    }

    public function testInputGroupSupportsValidationStatesAndFeedback(): void
    {
        $html = service('viewcell')->render(InputGroupCell::class, [
            'items' => [
                ['type' => 'text', 'content' => '@'],
                [
                    'type'            => 'input',
                    'id'              => 'validatedUsername',
                    'state'           => 'invalid',
                    'invalidFeedback' => 'Please choose a username.',
                ],
            ],
        ]);

        $this->assertStringContainsString('class="input-group has-validation"', $html);
        $this->assertStringContainsString('class="form-control is-invalid"', $html);
        $this->assertStringContainsString('aria-describedby="validatedUsernameInvalidFeedback"', $html);
        $this->assertStringContainsString('<div class="invalid-feedback" id="validatedUsernameInvalidFeedback">Please choose a username.</div>', $html);
    }
}
