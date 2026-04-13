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

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BootstrapCellsHelperTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        helper('bootstrap_cells');
    }

    public function testValidationItemAppliesInvalidStateAndFeedback(): void
    {
        $item = bootstrap_cell_validation_item([
            'name' => 'email',
        ], [
            'email' => 'Please provide a valid email address.',
        ]);

        $this->assertSame('invalid', $item['state']);
        $this->assertSame('Please provide a valid email address.', $item['invalidFeedback']);
    }

    public function testValidationItemsMapMultipleFields(): void
    {
        $items = bootstrap_cell_validation_items([
            ['name' => 'email'],
            ['name' => 'password'],
        ], [
            'email' => 'Email error',
        ]);

        $this->assertSame('invalid', $items[0]['state']);
        $this->assertArrayNotHasKey('state', $items[1]);
    }

    public function testValidationAlertReturnsAlertCellPayload(): void
    {
        $alert = bootstrap_cell_validation_alert([
            'email' => 'Email error',
            'password' => 'Password error',
        ]);

        $this->assertSame('danger', $alert['variant']);
        $this->assertSame('Validation errors', $alert['heading']);
        $this->assertFalse($alert['escape']);
        $this->assertStringContainsString('<ul class="mb-0">', $alert['content']);
        $this->assertStringContainsString('<li>Email error</li>', $alert['content']);
    }

    public function testFormItemAppliesOldValueAndValidationError(): void
    {
        $item = bootstrap_cell_form_item([
            'name' => 'email',
            'type' => 'input',
        ], [
            'email' => 'Email error',
        ], [
            'email' => 'jane@example.com',
        ]);

        $this->assertSame('jane@example.com', $item['value']);
        $this->assertSame('invalid', $item['state']);
        $this->assertSame('Email error', $item['invalidFeedback']);
    }

    public function testFormItemsApplyOldSelectionAndCheckedState(): void
    {
        $items = bootstrap_cell_form_items([
            [
                'name'    => 'country',
                'type'    => 'select',
                'options' => [
                    ['label' => 'France', 'value' => 'fr'],
                    ['label' => 'Belgium', 'value' => 'be'],
                ],
            ],
            [
                'name'  => 'terms',
                'type'  => 'checkbox',
                'value' => 'yes',
            ],
        ], null, [
            'country' => 'be',
            'terms'   => 'yes',
        ]);

        $this->assertFalse($items[0]['options'][0]['selected']);
        $this->assertTrue($items[0]['options'][1]['selected']);
        $this->assertTrue($items[1]['checked']);
    }

    public function testFormPayloadReturnsReadyToUseFormCellConfiguration(): void
    {
        $payload = bootstrap_cell_form_payload([
            ['name' => 'email'],
        ], [
            'email' => 'Email error',
        ], [
            'email' => 'jane@example.com',
        ], [
            'classes' => 'row g-3',
        ]);

        $this->assertSame('row g-3', $payload['classes']);
        $this->assertSame('jane@example.com', $payload['items'][0]['value']);
        $this->assertSame('Email error', $payload['items'][0]['invalidFeedback']);
    }

    public function testTablePayloadBuildsHeadersRowsAndActionsFromResultLikeObject(): void
    {
        $result = new class () {
            public function getFieldNames(): array
            {
                return ['id', 'first_name', 'email'];
            }

            public function getResultArray(): array
            {
                return [
                    ['id' => 1, 'first_name' => 'Jane', 'email' => 'jane@example.com'],
                ];
            }
        };

        $payload = bootstrap_cell_table_payload($result, [
            'hidden'  => ['id'],
            'actions' => static fn (array $row): array => [
                ['label' => 'Edit', 'href' => '/users/' . $row['id'] . '/edit'],
            ],
        ]);

        $this->assertSame('First Name', $payload['headers'][0]['label']);
        $this->assertSame('Email', $payload['headers'][1]['label']);
        $this->assertSame('Jane', $payload['rows'][0]['cells'][0]['content']);
        $this->assertSame('jane@example.com', $payload['rows'][0]['cells'][1]['content']);
        $this->assertSame('/users/1/edit', $payload['rows'][0]['actions'][0]['href']);
    }
}
