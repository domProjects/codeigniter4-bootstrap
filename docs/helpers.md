# Helpers Reference

Use these helpers to bridge CodeIgniter 4 validation, old input, and database results with the package Cells.

Load the helper with:

```php
helper('bootstrap_cells');
```

## Validation Helpers

- `bootstrap_cell_validation_errors($validation)`: normalize CI4 validation errors to an array
- `bootstrap_cell_validation_item($item, $validation)`: enrich one field config with `state=invalid` and `invalidFeedback`
- `bootstrap_cell_validation_items($items, $validation)`: apply the same mapping to a list of field configs
- `bootstrap_cell_validation_alert($validation)`: generate an `AlertCell` payload for a validation summary

Example:

```php
helper('bootstrap_cells');

$alert = bootstrap_cell_validation_alert(service('validation'));

if ($alert !== []) {
    echo view_cell(\domProjects\CodeIgniterBootstrap\Cells\AlertCell::class, $alert);
}
```

## Form Payload Helpers

- `bootstrap_cell_form_old_value($field, $oldInput)`: resolve an old value from CI4 or an explicit array
- `bootstrap_cell_form_item($item, $validation, $oldInput)`: enrich one `FormCell` item with old value, `selected`, `checked`, and validation state
- `bootstrap_cell_form_items($items, $validation, $oldInput)`: apply the same mapping to a list of `FormCell` items
- `bootstrap_cell_form_payload($items, $validation, $oldInput, $form)`: generate a ready-to-use payload for `FormCell`

Example:

```php
helper('bootstrap_cells');

$form = bootstrap_cell_form_payload([
    ['name' => 'email', 'label' => 'Email'],
    ['name' => 'password', 'label' => 'Password', 'type' => 'password'],
], service('validation'));

echo view_cell(\domProjects\CodeIgniterBootstrap\Cells\FormCell::class, $form + [
    'classes' => 'row g-3',
]);
```

## Table Payload Helpers

- `bootstrap_cell_table_payload($result, $options)`: build a ready-to-use `TableCell` payload from a CI4 Result or an array of rows
- `bootstrap_cell_table_headers($fieldNames, $options)`: build normalized table headers
- `bootstrap_cell_table_rows($rows, $options)`: build normalized table rows, including optional actions
- `bootstrap_cell_humanize($value)`: convert a field name like `first_name` to `First Name`

Example:

```php
helper('bootstrap_cells');

$table = bootstrap_cell_table_payload($query->get(), [
    'hidden'  => ['id'],
    'actions' => static fn (array $row): array => [
        ['label' => 'Edit', 'href' => '/users/' . $row['id'] . '/edit'],
    ],
    'responsive' => true,
    'stacked'    => true,
]);

echo view_cell(\domProjects\CodeIgniterBootstrap\Cells\TableCell::class, $table);
```

## See Also

- [Documentation Index](index.md)
- [Getting Started](getting-started.md)
- [Cells Reference](cells/index.md)
