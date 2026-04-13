<?php

declare(strict_types=1);

if (! function_exists('bootstrap_cell_validation_errors')) {
    /**
     * Normalizes validation errors from CI4 or a plain array to a string map.
     *
     * @return array<string, string>
     */
    function bootstrap_cell_validation_errors(mixed $validation = null): array
    {
        if (is_array($validation)) {
            return array_filter(array_map(
                static fn (mixed $message): string => is_scalar($message) ? (string) $message : '',
                $validation
            ));
        }

        if (is_object($validation) && method_exists($validation, 'getErrors')) {
            $errors = $validation->getErrors();

            if (is_array($errors)) {
                return array_filter(array_map(
                    static fn (mixed $message): string => is_scalar($message) ? (string) $message : '',
                    $errors
                ));
            }
        }

        return [];
    }
}

if (! function_exists('bootstrap_cell_validation_item')) {
    /**
     * Applies validation state and feedback to a single FormCell-like item config.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    function bootstrap_cell_validation_item(array $item, mixed $validation = null, ?string $field = null): array
    {
        $errors = bootstrap_cell_validation_errors($validation);
        $field  = $field
            ?? (is_string($item['validationField'] ?? null) ? $item['validationField'] : null)
            ?? (is_string($item['name'] ?? null) ? $item['name'] : null)
            ?? (is_string($item['id'] ?? null) ? $item['id'] : null);

        if ($field === null || $field === '' || ! array_key_exists($field, $errors)) {
            return $item;
        }

        if (($item['state'] ?? '') === '') {
            $item['state'] = 'invalid';
        }

        if (($item['invalidFeedback'] ?? '') === '') {
            $item['invalidFeedback'] = $errors[$field];
        }

        return $item;
    }
}

if (! function_exists('bootstrap_cell_validation_items')) {
    /**
     * Applies validation state and feedback to a list of FormCell-like item configs.
     *
     * @param list<array<string, mixed>> $items
     * @return list<array<string, mixed>>
     */
    function bootstrap_cell_validation_items(array $items, mixed $validation = null): array
    {
        foreach ($items as $index => $item) {
            $items[$index] = bootstrap_cell_validation_item($item, $validation);
        }

        return $items;
    }
}

if (! function_exists('bootstrap_cell_validation_alert')) {
    /**
     * Builds an AlertCell payload containing a validation error summary list.
     *
     * @return array<string, mixed>
     */
    function bootstrap_cell_validation_alert(mixed $validation = null, string $heading = 'Validation errors'): array
    {
        $errors = bootstrap_cell_validation_errors($validation);

        if ($errors === []) {
            return [];
        }

        $items = '';

        foreach ($errors as $message) {
            $items .= '<li>' . esc($message) . '</li>';
        }

        return [
            'variant' => 'danger',
            'heading' => $heading,
            'content' => '<ul class="mb-0">' . $items . '</ul>',
            'escape'  => false,
        ];
    }
}

if (! function_exists('bootstrap_cell_form_old_value')) {
    /**
     * Resolves an old input value from an explicit payload or CI4 helper functions.
     */
    function bootstrap_cell_form_old_value(?string $field, mixed $oldInput = null, mixed $default = null): mixed
    {
        if ($field === null || $field === '') {
            return $default;
        }

        if (is_array($oldInput) && array_key_exists($field, $oldInput)) {
            return $oldInput[$field];
        }

        if (function_exists('old')) {
            $value = old($field);

            if ($value !== null) {
                return $value;
            }
        }

        if (function_exists('set_value')) {
            $value = set_value($field, is_scalar($default) ? (string) $default : '');

            if ($value !== '') {
                return $value;
            }
        }

        return $default;
    }
}

if (! function_exists('bootstrap_cell_form_item')) {
    /**
     * Hydrates one form item with validation feedback and old submitted values.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    function bootstrap_cell_form_item(array $item, mixed $validation = null, mixed $oldInput = null, ?string $field = null): array
    {
        $field = $field
            ?? (is_string($item['validationField'] ?? null) ? $item['validationField'] : null)
            ?? (is_string($item['name'] ?? null) ? $item['name'] : null)
            ?? (is_string($item['id'] ?? null) ? $item['id'] : null);

        $item = bootstrap_cell_validation_item($item, $validation, $field);

        $type     = strtolower((string) ($item['type'] ?? 'input'));
        $oldValue = bootstrap_cell_form_old_value($field, $oldInput, $item['value'] ?? null);

        if ($oldValue === null) {
            return $item;
        }

        if (in_array($type, ['select'], true) && is_array($item['options'] ?? null)) {
            foreach ($item['options'] as $index => $option) {
                if (! is_array($option)) {
                    $option = ['label' => $option, 'value' => $option];
                }

                $optionValue = array_key_exists('value', $option) ? $option['value'] : ($option['label'] ?? null);
                $option['selected'] = bootstrap_cell_form_value_matches($oldValue, $optionValue);
                $item['options'][$index] = $option;
            }

            return $item;
        }

        if (in_array($type, ['checkbox', 'radio', 'switch'], true)) {
            $item['checked'] = bootstrap_cell_form_value_matches($oldValue, $item['value'] ?? null);

            return $item;
        }

        $item['value'] = is_array($oldValue) ? ($item['value'] ?? null) : $oldValue;

        return $item;
    }
}

if (! function_exists('bootstrap_cell_form_items')) {
    /**
     * Hydrates a full FormCell items array with validation feedback and old values.
     *
     * @param list<array<string, mixed>> $items
     * @return list<array<string, mixed>>
     */
    function bootstrap_cell_form_items(array $items, mixed $validation = null, mixed $oldInput = null): array
    {
        foreach ($items as $index => $item) {
            $items[$index] = bootstrap_cell_form_item($item, $validation, $oldInput);
        }

        return $items;
    }
}

if (! function_exists('bootstrap_cell_form_payload')) {
    /**
     * Builds a ready-to-use FormCell payload from field items and form options.
     *
     * @param list<array<string, mixed>> $items
     * @param array<string, mixed> $form
     * @return array<string, mixed>
     */
    function bootstrap_cell_form_payload(array $items, mixed $validation = null, mixed $oldInput = null, array $form = []): array
    {
        $form['items'] = bootstrap_cell_form_items($items, $validation, $oldInput);

        return $form;
    }
}

if (! function_exists('bootstrap_cell_form_value_matches')) {
    /**
     * Compares old input values against a candidate option or checkbox value.
     */
    function bootstrap_cell_form_value_matches(mixed $oldValue, mixed $candidate): bool
    {
        if (is_array($oldValue)) {
            foreach ($oldValue as $value) {
                if ((string) $value === (string) $candidate) {
                    return true;
                }
            }

            return false;
        }

        if ($candidate === null || $candidate === '') {
            if (is_bool($oldValue)) {
                return $oldValue;
            }

            if (is_int($oldValue)) {
                return $oldValue !== 0;
            }

            if (is_string($oldValue)) {
                return ! in_array(strtolower(trim($oldValue)), ['', '0', 'false', 'off', 'no'], true);
            }

            return $oldValue !== null;
        }

        return (string) $oldValue === (string) $candidate;
    }
}

if (! function_exists('bootstrap_cell_table_payload')) {
    /**
     * Builds a ready-to-use TableCell payload from a CI4 result object or row array.
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    function bootstrap_cell_table_payload(mixed $result, array $options = []): array
    {
        $rows = [];
        $headers = [];

        if (is_object($result) && method_exists($result, 'getResultArray')) {
            $rows = $result->getResultArray();

            if (method_exists($result, 'getFieldNames')) {
                $fieldNames = $result->getFieldNames();

                if (is_array($fieldNames)) {
                    $headers = array_values(array_filter(array_map(
                        static fn (mixed $name): string => is_scalar($name) ? (string) $name : '',
                        $fieldNames
                    )));
                }
            }
        } elseif (is_array($result)) {
            $rows = $result;
        }

        if ($headers === [] && isset($rows[0]) && is_array($rows[0])) {
            $headers = array_map(static fn (string $key): string => $key, array_keys($rows[0]));
        }

        $headerOptions = is_array($options['headers'] ?? null) ? $options['headers'] : [];

        if (! array_key_exists('hidden', $headerOptions) && array_key_exists('hidden', $options)) {
            $headerOptions['hidden'] = $options['hidden'];
        }

        $payload = [
            'headers' => bootstrap_cell_table_headers($headers, $headerOptions),
            'rows'    => bootstrap_cell_table_rows($rows, $options),
        ];

        foreach ([
            'caption',
            'captionTop',
            'variant',
            'striped',
            'stripedColumns',
            'hover',
            'bordered',
            'borderless',
            'small',
            'responsive',
            'responsiveBreakpoint',
            'stacked',
            'stackedBreakpoint',
            'emptyMessage',
            'emptyClasses',
            'actionsHeader',
            'actionsCellClasses',
            'classes',
            'wrapperClasses',
            'headVariant',
            'escape',
        ] as $key) {
            if (array_key_exists($key, $options)) {
                $payload[$key] = $options[$key];
            }
        }

        return $payload;
    }
}

if (! function_exists('bootstrap_cell_table_headers')) {
    /**
     * Builds normalized table headers from field names and header options.
     *
     * @param list<string> $fieldNames
     * @param array<string, mixed> $headerOptions
     * @return list<array<string, mixed>>
     */
    function bootstrap_cell_table_headers(array $fieldNames, array $headerOptions = []): array
    {
        $headers = [];
        $labels  = is_array($headerOptions['labels'] ?? null) ? $headerOptions['labels'] : [];
        $classes = is_array($headerOptions['classes'] ?? null) ? $headerOptions['classes'] : [];
        $hidden  = is_array($headerOptions['hidden'] ?? null) ? $headerOptions['hidden'] : [];

        foreach ($fieldNames as $field) {
            if (in_array($field, $hidden, true)) {
                continue;
            }

            $headers[] = [
                'label'   => array_key_exists($field, $labels) ? (string) $labels[$field] : bootstrap_cell_humanize($field),
                'scope'   => 'col',
                'classes' => array_key_exists($field, $classes) ? (string) $classes[$field] : '',
            ];
        }

        return $headers;
    }
}

if (! function_exists('bootstrap_cell_table_rows')) {
    /**
     * Builds normalized table rows, optionally enriching them with row classes and actions.
     *
     * @param array<int, mixed> $rows
     * @param array<string, mixed> $options
     * @return list<array<string, mixed>>
     */
    function bootstrap_cell_table_rows(array $rows, array $options = []): array
    {
        $normalized = [];
        $hidden     = is_array($options['hidden'] ?? null) ? $options['hidden'] : [];
        $rowClasses = is_callable($options['rowClasses'] ?? null) ? $options['rowClasses'] : null;
        $actions    = is_callable($options['actions'] ?? null) ? $options['actions'] : null;
        $columns    = is_array($options['columns'] ?? null) ? $options['columns'] : [];

        foreach ($rows as $index => $row) {
            if (is_object($row)) {
                $row = get_object_vars($row);
            }

            if (! is_array($row)) {
                continue;
            }

            $cells = [];

            if ($columns !== []) {
                foreach ($columns as $column) {
                    if (is_string($column)) {
                        $field = $column;
                        $cell  = [
                            'content' => array_key_exists($field, $row) ? (string) $row[$field] : '',
                        ];
                    } elseif (is_array($column)) {
                        $field = is_string($column['field'] ?? null) ? $column['field'] : null;
                        $cell = [
                            'content' => $field !== null && array_key_exists($field, $row)
                                ? (string) $row[$field]
                                : (string) ($column['content'] ?? ''),
                            'tag'     => $column['tag'] ?? 'td',
                            'scope'   => $column['scope'] ?? '',
                            'classes' => $column['classes'] ?? '',
                        ];

                        if (is_callable($column['render'] ?? null)) {
                            $cell['content'] = (string) $column['render']($row, $index);
                        }
                    } else {
                        continue;
                    }

                    if ($cell['content'] === '') {
                        $cell['content'] = ' ';
                    }

                    $cells[] = $cell;
                }
            } else {
                foreach ($row as $field => $value) {
                    if (in_array((string) $field, $hidden, true)) {
                        continue;
                    }

                    $cells[] = [
                        'content' => is_scalar($value) || $value === null ? (string) $value : json_encode($value),
                    ];
                }
            }

            $rowPayload = [
                'cells' => $cells,
            ];

            if ($rowClasses !== null) {
                $rowPayload['classes'] = (string) $rowClasses($row, $index);
            }

            if ($actions !== null) {
                $actionsPayload = $actions($row, $index);

                if (is_array($actionsPayload) && $actionsPayload !== []) {
                    $rowPayload['actions'] = $actionsPayload;
                }
            }

            $normalized[] = $rowPayload;
        }

        return $normalized;
    }
}

if (! function_exists('bootstrap_cell_humanize')) {
    /**
     * Converts a field name like first_name to a readable label like First Name.
     */
    function bootstrap_cell_humanize(string $value): string
    {
        $value = preg_replace('/[_\-]+/', ' ', trim($value)) ?? $value;
        $value = preg_replace('/(?<!^)[A-Z]/', ' $0', $value) ?? $value;

        return ucwords(trim($value));
    }
}
