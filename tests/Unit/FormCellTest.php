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
use domProjects\CodeIgniterBootstrap\Cells\FormCell;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FormCellTest extends CIUnitTestCase
{
    public function testFormRendersExpectedBootstrapMarkup(): void
    {
        $html = service('viewcell')->render(FormCell::class, [
            'action' => '/login',
            'items'  => [
                [
                    'label'       => 'Email address',
                    'id'          => 'loginEmail',
                    'name'        => 'email',
                    'inputType'   => 'email',
                    'help'        => 'We will never share your email.',
                    'placeholder' => 'name@example.com',
                ],
                [
                    'label'     => 'Password',
                    'id'        => 'loginPassword',
                    'name'      => 'password',
                    'inputType' => 'password',
                ],
                [
                    'type'  => 'submit',
                    'label' => 'Submit',
                ],
            ],
        ]);

        $this->assertStringContainsString('<form method="post" action="/login">', $html);
        $this->assertStringContainsString('class="form-label"', $html);
        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringContainsString('aria-describedby="loginEmailHelp"', $html);
        $this->assertStringContainsString('<div class="form-text" id="loginEmailHelp">We will never share your email.</div>', $html);
        $this->assertStringContainsString('class="btn btn-primary"', $html);
    }

    public function testFormSupportsCheckboxSelectTextareaAndDisabledFieldset(): void
    {
        $html = service('viewcell')->render(FormCell::class, [
            'disabled' => true,
            'items'    => [
                [
                    'type'    => 'checkbox',
                    'id'      => 'agreeTerms',
                    'label'   => 'Agree to terms',
                    'checked' => true,
                ],
                [
                    'type'    => 'select',
                    'id'      => 'country',
                    'label'   => 'Country',
                    'options' => [
                        ['label' => 'France', 'selected' => true],
                        ['label' => 'Belgium'],
                    ],
                ],
                [
                    'type'  => 'textarea',
                    'id'    => 'bio',
                    'label' => 'Bio',
                    'rows'  => 3,
                    'value' => 'Hello',
                ],
            ],
        ]);

        $this->assertStringContainsString('<fieldset disabled="disabled">', $html);
        $this->assertStringContainsString('class="form-check mb-3"', $html);
        $this->assertStringContainsString('checked="checked"', $html);
        $this->assertStringContainsString('class="form-select"', $html);
        $this->assertStringContainsString('selected="selected"', $html);
        $this->assertStringContainsString('<textarea class="form-control" id="bio" rows="3">Hello</textarea>', $html);
    }

    public function testFormSupportsValidationStatesAndFeedback(): void
    {
        $html = service('viewcell')->render(FormCell::class, [
            'validated' => true,
            'items'     => [
                [
                    'label'           => 'Email',
                    'id'              => 'validatedEmail',
                    'name'            => 'email',
                    'inputType'       => 'email',
                    'state'           => 'invalid',
                    'help'            => 'Use your work email.',
                    'invalidFeedback' => 'Please provide a valid email.',
                ],
                [
                    'type'            => 'checkbox',
                    'id'              => 'validatedCheck',
                    'label'           => 'Accept terms',
                    'state'           => 'invalid',
                    'invalidFeedback' => 'You must agree before submitting.',
                ],
            ],
        ]);

        $this->assertStringContainsString('<form method="post" class="was-validated">', $html);
        $this->assertStringContainsString('class="form-control is-invalid"', $html);
        $this->assertStringContainsString('aria-describedby="validatedEmailHelp validatedEmailInvalidFeedback"', $html);
        $this->assertStringContainsString('<div class="invalid-feedback" id="validatedEmailInvalidFeedback">Please provide a valid email.</div>', $html);
        $this->assertStringContainsString('class="form-check-input is-invalid"', $html);
        $this->assertStringContainsString('<div class="invalid-feedback" id="validatedCheckInvalidFeedback">You must agree before submitting.</div>', $html);
    }

    public function testFormSupportsFloatingLabels(): void
    {
        $html = service('viewcell')->render(FormCell::class, [
            'items' => [
                [
                    'label'     => 'Email address',
                    'id'        => 'floatingEmail',
                    'name'      => 'email',
                    'inputType' => 'email',
                    'floating'  => true,
                ],
                [
                    'type'     => 'select',
                    'label'    => 'Country',
                    'id'       => 'floatingCountry',
                    'floating' => true,
                    'options'  => [
                        ['label' => 'Open this select menu'],
                        ['label' => 'France'],
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString('<div class="form-floating mb-3">', $html);
        $this->assertStringContainsString('<input class="form-control" type="email" id="floatingEmail" name="email" placeholder=" ">', $html);
        $this->assertStringContainsString('<label for="floatingEmail">Email address</label>', $html);
        $this->assertStringContainsString('<select class="form-select" id="floatingCountry">', $html);
        $this->assertStringContainsString('<label for="floatingCountry">Country</label>', $html);
    }

    public function testFormSupportsAdvancedChecksAndRadios(): void
    {
        $html = service('viewcell')->render(FormCell::class, [
            'items' => [
                [
                    'type'   => 'checkbox',
                    'id'     => 'inlineCheck',
                    'label'  => 'Inline checkbox',
                    'inline' => true,
                ],
                [
                    'type'    => 'radio',
                    'id'      => 'reverseRadio',
                    'label'   => 'Reverse radio',
                    'reverse' => true,
                    'state'   => 'valid',
                ],
                [
                    'type'            => 'switch',
                    'id'              => 'newsletterSwitch',
                    'label'           => 'Newsletter',
                    'invalidFeedback' => 'Switch feedback',
                ],
            ],
        ]);

        $this->assertStringContainsString('class="form-check form-check-inline"', $html);
        $this->assertStringContainsString('class="form-check form-check-reverse mb-3"', $html);
        $this->assertStringContainsString('class="form-check-input is-valid"', $html);
        $this->assertStringContainsString('class="form-check form-switch mb-3"', $html);
        $this->assertStringContainsString('<div class="invalid-feedback" id="newsletterSwitchInvalidFeedback">Switch feedback</div>', $html);
    }

    public function testFormSupportsColumnLayouts(): void
    {
        $html = service('viewcell')->render(FormCell::class, [
            'classes' => 'row g-3',
            'items'   => [
                [
                    'label'  => 'First name',
                    'id'     => 'firstName',
                    'column' => 'md-6',
                ],
                [
                    'label'        => 'Last name',
                    'id'           => 'lastName',
                    'columnClasses'=> 'col-md-6',
                ],
                [
                    'type'         => 'submit',
                    'label'        => 'Save',
                    'column'       => '12',
                ],
            ],
        ]);

        $this->assertStringContainsString('<form method="post" class="row g-3">', $html);
        $this->assertStringContainsString('<div class="col-md-6">', $html);
        $this->assertStringContainsString('<label class="form-label" for="firstName">First name</label>', $html);
        $this->assertStringContainsString('<div class="col-12">', $html);
        $this->assertStringContainsString('class="btn btn-primary"', $html);
    }
}
