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

namespace domProjects\CodeIgniterBootstrap\Cells;

use CodeIgniter\View\Cells\Cell;

/**
 * Renders a Bootstrap 5 form component through a CodeIgniter 4 Cell.
 */
final class FormCell extends Cell
{
    protected string $view = 'form';

    public mixed $items = [];

    public mixed $action = null;

    public mixed $method = 'post';

    public mixed $classes = '';

    public mixed $id = null;

    public mixed $enctype = null;

    public mixed $novalidate = false;

    public mixed $validated = false;

    public mixed $autocomplete = null;

    public mixed $disabled = false;

    public mixed $escape = true;

    /**
     * Prepares form attributes and normalized items for the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'formAttributes'   => $this->buildFormAttributes(),
            'fieldsetDisabled' => $this->normalizeBool($this->disabled),
            'items'            => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Builds the HTML attributes applied to the `form` element.
     *
     * @return array<string, string>
     */
    private function buildFormAttributes(): array
    {
        $attributes = [
            'method' => $this->resolveNonEmptyString($this->method, 'post'),
        ];

        foreach (['action', 'id', 'enctype', 'autocomplete'] as $attribute) {
            $value = trim($this->resolveOptionalString($this->{$attribute}));

            if ($value !== '') {
                $attributes[$attribute] = $value;
            }
        }

        $classes = $this->normalizeClasses($this->resolveOptionalString($this->classes));
        $classList = [];

        if ($classes !== '') {
            $classList[] = $classes;
        }

        if ($this->normalizeBool($this->validated)) {
            $classList[] = 'was-validated';
        }

        if ($classList !== []) {
            $attributes['class'] = $this->normalizeClasses(implode(' ', $classList));
        }

        if ($this->normalizeBool($this->novalidate)) {
            $attributes['novalidate'] = 'novalidate';
        }

        return $attributes;
    }

    /**
     * Normalizes the configured form items into the structure expected by the view.
     *
     * @return list<array<string, mixed>>
     */
    private function normalizeItems(bool $escape): array
    {
        if (! is_array($this->items)) {
            return [];
        }

        $items = [];

        foreach ($this->items as $item) {
            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (! is_array($item)) {
                continue;
            }

            $kind = strtolower(trim($this->resolveOptionalString($item['type'] ?? 'input')));

            if ($kind === 'hidden') {
                $items[] = [
                    'kind'       => 'hidden',
                    'attributes' => $this->buildControlAttributes($item, 'input', false),
                    'columnClasses' => '',
                ];

                continue;
            }

            if (in_array($kind, ['checkbox', 'radio', 'switch'], true)) {
                $items[] = $this->normalizeCheckItem($item, $escape, $kind);

                continue;
            }

            if (in_array($kind, ['button', 'submit', 'reset'], true)) {
                $items[] = $this->normalizeButtonItem($item, $escape, $kind);

                continue;
            }

            if ($kind === 'select') {
                $items[] = $this->normalizeSelectItem($item, $escape);

                continue;
            }

            if ($kind === 'textarea') {
                $items[] = $this->normalizeTextareaItem($item, $escape);

                continue;
            }

            $items[] = $this->normalizeInputItem($item, $escape);
        }

        return $items;
    }

    /**
     * Normalizes a standard input field.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function normalizeInputItem(array $item, bool $escape): array
    {
        $floating = $this->normalizeBool($item['floating'] ?? false);
        $label = $this->resolveOptionalString($item['label'] ?? '');
        $help  = $this->resolveOptionalString($item['help'] ?? '');
        $validFeedback   = $this->resolveOptionalString($item['validFeedback'] ?? '');
        $invalidFeedback = $this->resolveOptionalString($item['invalidFeedback'] ?? '');

        return [
            'kind'            => 'control',
            'columnClasses'   => $this->buildColumnClasses($item),
            'wrapperClasses'  => $this->buildControlWrapperClasses($item, $floating),
            'labelHtml'       => $label === '' ? '' : ($escape ? esc($label) : $label),
            'labelAttributes' => $this->buildLabelAttributes($item, $floating),
            'tag'             => 'input',
            'attributes'      => $this->buildControlAttributes($item, 'input'),
            'controlHtml'     => '',
            'labelAfterControl' => $floating,
            'helpHtml'        => $help === '' ? '' : ($escape ? esc($help) : $help),
            'helpAttributes'  => $this->buildHelpAttributes($item),
            'validFeedbackHtml' => $validFeedback === '' ? '' : ($escape ? esc($validFeedback) : $validFeedback),
            'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
            'invalidFeedbackHtml' => $invalidFeedback === '' ? '' : ($escape ? esc($invalidFeedback) : $invalidFeedback),
            'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
        ];
    }

    /**
     * Normalizes a textarea field with its Bootstrap wrapper data.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function normalizeTextareaItem(array $item, bool $escape): array
    {
        $floating = $this->normalizeBool($item['floating'] ?? false);
        $label = $this->resolveOptionalString($item['label'] ?? '');
        $help  = $this->resolveOptionalString($item['help'] ?? '');
        $value = $this->resolveOptionalString($item['value'] ?? $item['content'] ?? '');
        $validFeedback   = $this->resolveOptionalString($item['validFeedback'] ?? '');
        $invalidFeedback = $this->resolveOptionalString($item['invalidFeedback'] ?? '');

        return [
            'kind'            => 'control',
            'columnClasses'   => $this->buildColumnClasses($item),
            'wrapperClasses'  => $this->buildControlWrapperClasses($item, $floating),
            'labelHtml'       => $label === '' ? '' : ($escape ? esc($label) : $label),
            'labelAttributes' => $this->buildLabelAttributes($item, $floating),
            'tag'             => 'textarea',
            'attributes'      => $this->buildControlAttributes($item, 'textarea'),
            'controlHtml'     => $escape ? esc($value) : $value,
            'labelAfterControl' => $floating,
            'helpHtml'        => $help === '' ? '' : ($escape ? esc($help) : $help),
            'helpAttributes'  => $this->buildHelpAttributes($item),
            'validFeedbackHtml' => $validFeedback === '' ? '' : ($escape ? esc($validFeedback) : $validFeedback),
            'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
            'invalidFeedbackHtml' => $invalidFeedback === '' ? '' : ($escape ? esc($invalidFeedback) : $invalidFeedback),
            'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
        ];
    }

    /**
     * Normalizes a `select` field and its options.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function normalizeSelectItem(array $item, bool $escape): array
    {
        $floating = $this->normalizeBool($item['floating'] ?? false);
        $label = $this->resolveOptionalString($item['label'] ?? '');
        $help  = $this->resolveOptionalString($item['help'] ?? '');
        $validFeedback   = $this->resolveOptionalString($item['validFeedback'] ?? '');
        $invalidFeedback = $this->resolveOptionalString($item['invalidFeedback'] ?? '');

        return [
            'kind'            => 'select',
            'columnClasses'   => $this->buildColumnClasses($item),
            'wrapperClasses'  => $this->buildControlWrapperClasses($item, $floating),
            'labelHtml'       => $label === '' ? '' : ($escape ? esc($label) : $label),
            'labelAttributes' => $this->buildLabelAttributes($item, $floating),
            'tag'             => 'select',
            'attributes'      => $this->buildSelectAttributes($item),
            'options'         => $this->normalizeOptions($item['options'] ?? [], $escape),
            'labelAfterControl' => $floating,
            'helpHtml'        => $help === '' ? '' : ($escape ? esc($help) : $help),
            'helpAttributes'  => $this->buildHelpAttributes($item),
            'validFeedbackHtml' => $validFeedback === '' ? '' : ($escape ? esc($validFeedback) : $validFeedback),
            'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
            'invalidFeedbackHtml' => $invalidFeedback === '' ? '' : ($escape ? esc($invalidFeedback) : $invalidFeedback),
            'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
        ];
    }

    /**
     * Normalizes a `checkbox`, `radio`, or `switch` control.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function normalizeCheckItem(array $item, bool $escape, string $kind): array
    {
        $label = $this->resolveOptionalString($item['label'] ?? '');
        $help  = $this->resolveOptionalString($item['help'] ?? '');
        $validFeedback   = $this->resolveOptionalString($item['validFeedback'] ?? '');
        $invalidFeedback = $this->resolveOptionalString($item['invalidFeedback'] ?? '');

        return [
            'kind'            => 'check',
            'columnClasses'   => $this->buildColumnClasses($item),
            'wrapperClasses'  => $this->buildCheckWrapperClasses($item, $kind),
            'labelHtml'       => $label === '' ? '' : ($escape ? esc($label) : $label),
            'labelAttributes' => $this->buildCheckLabelAttributes($item),
            'tag'             => 'input',
            'attributes'      => $this->buildCheckAttributes($item, $kind === 'switch' ? 'checkbox' : $kind),
            'helpHtml'        => $help === '' ? '' : ($escape ? esc($help) : $help),
            'helpAttributes'  => $this->buildHelpAttributes($item),
            'validFeedbackHtml' => $validFeedback === '' ? '' : ($escape ? esc($validFeedback) : $validFeedback),
            'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
            'invalidFeedbackHtml' => $invalidFeedback === '' ? '' : ($escape ? esc($invalidFeedback) : $invalidFeedback),
            'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
        ];
    }

    /**
     * Normalizes a simple form button item.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function normalizeButtonItem(array $item, bool $escape, string $kind): array
    {
        $label = $this->resolveOptionalString($item['label'] ?? $item['content'] ?? 'Submit');
        $variant = $this->normalizeVariant($this->resolveNonEmptyString($item['variant'] ?? '', 'primary'));
        $classes = ['btn', 'btn-' . $variant];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        $attributes = [
            'type'  => $kind === 'button' ? $this->resolveNonEmptyString($item['buttonType'] ?? '', 'button') : $kind,
            'class' => $this->normalizeClasses(implode(' ', $classes)),
        ];

        $id = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        if ($this->normalizeBool($item['disabled'] ?? false)) {
            $attributes['disabled'] = 'disabled';
        }

        return [
            'kind'           => 'button',
            'columnClasses'  => $this->buildColumnClasses($item),
            'wrapperClasses' => $this->buildWrapperClasses($item, ''),
            'labelHtml'      => $escape ? esc($label) : $label,
            'attributes'     => $attributes,
        ];
    }

    /**
     * Resolves the Bootstrap grid classes for a field.
     *
     * @param array<string, mixed> $item
     */
    private function buildColumnClasses(array $item): string
    {
        $classes = [];
        $columnClasses = $this->normalizeClasses($this->resolveOptionalString($item['columnClasses'] ?? ''));

        if ($columnClasses !== '') {
            $classes[] = $columnClasses;
        }

        $column = strtolower(trim($this->resolveOptionalString($item['column'] ?? '')));

        if ($column !== '') {
            if (preg_match('/^\d+$/', $column) === 1) {
                $classes[] = 'col-' . $column;
            } elseif ($column === 'auto') {
                $classes[] = 'col-auto';
            } elseif (preg_match('/^(sm|md|lg|xl|xxl)-(\d+|auto)$/', $column) === 1) {
                $classes[] = 'col-' . $column;
            } else {
                $classes[] = $column;
            }
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the wrapper classes for a standard control.
     *
     * @param array<string, mixed> $item
     */
    private function buildControlWrapperClasses(array $item, bool $floating): string
    {
        $classes = $floating ? ['form-floating', 'mb-3'] : ['mb-3'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['wrapperClasses'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the wrapper classes for a check-like control.
     *
     * @param array<string, mixed> $item
     */
    private function buildCheckWrapperClasses(array $item, string $kind): string
    {
        $classes = ['form-check'];

        if ($kind === 'switch') {
            $classes[] = 'form-switch';
        }

        if ($this->normalizeBool($item['reverse'] ?? false)) {
            $classes[] = 'form-check-reverse';
        }

        if ($this->normalizeBool($item['inline'] ?? $item['checkInline'] ?? false)) {
            $classes[] = 'form-check-inline';
        } else {
            $classes[] = 'mb-3';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($item['wrapperClasses'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Merges default wrapper classes with caller-provided classes.
     *
     * @param array<string, mixed> $item
     */
    private function buildWrapperClasses(array $item, string $default): string
    {
        $classes = [];

        if ($default !== '') {
            $classes[] = $default;
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($item['wrapperClasses'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the attributes for a field label.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildLabelAttributes(array $item, bool $floating = false): array
    {
        $attributes = [];
        $defaultClass = $floating ? '' : 'form-label';
        $class = $this->resolveOptionalString($item['labelClasses'] ?? $defaultClass);

        if (trim($class) !== '') {
            $attributes['class'] = $this->normalizeClasses($class);
        }

        $for = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($for !== '') {
            $attributes['for'] = $for;
        }

        return $attributes;
    }

    /**
     * Builds the attributes for a checkbox or radio label.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildCheckLabelAttributes(array $item): array
    {
        $attributes = [
            'class' => $this->resolveNonEmptyString($item['labelClasses'] ?? '', 'form-check-label'),
        ];

        $for = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($for !== '') {
            $attributes['for'] = $for;
        }

        return $attributes;
    }

    /**
     * Builds the attributes for a field help block.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildHelpAttributes(array $item): array
    {
        $attributes = [
            'class' => $this->resolveNonEmptyString($item['helpClasses'] ?? '', 'form-text'),
        ];

        $id = $this->resolveHelpId($item);

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        return $attributes;
    }

    /**
     * Builds the attributes for Bootstrap validation feedback blocks.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildFeedbackAttributes(array $item, string $type): array
    {
        $defaultClass = $type === 'valid' ? 'valid-feedback' : 'invalid-feedback';
        $classKey     = $type === 'valid' ? 'validFeedbackClasses' : 'invalidFeedbackClasses';
        $attributes   = [
            'class' => $this->resolveNonEmptyString($item[$classKey] ?? '', $defaultClass),
        ];

        $id = $this->resolveFeedbackId($item, $type);

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        return $attributes;
    }

    /**
     * Builds the HTML attributes for a standard form control.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildControlAttributes(array $item, string $kind, bool $withDescribedby = true): array
    {
        $classes = [$kind === 'textarea' ? 'form-control' : $this->resolveControlClass($item)];
        $state   = $this->normalizeState($item);
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($state !== '') {
            $classes[] = 'is-' . $state;
        }

        if ($extra !== '') {
            $classes[] = $extra;
        }

        $attributes = [
            'class' => $this->normalizeClasses(implode(' ', $classes)),
        ];

        if ($kind === 'input') {
            $attributes['type'] = $this->resolveInputType($item);
        }

        $attributesToCopy = $kind === 'textarea'
            ? ['id', 'name', 'placeholder', 'autocomplete']
            : ['id', 'name', 'placeholder', 'value', 'autocomplete', 'accept', 'min', 'max', 'step', 'pattern'];

        foreach ($attributesToCopy as $attribute) {
            $value = trim($this->resolveOptionalString($item[$attribute] ?? ''));

            if ($value !== '') {
                $attributes[$attribute] = $value;
            }
        }

        $ariaLabel = trim($this->resolveOptionalString($item['ariaLabel'] ?? ''));

        if ($ariaLabel !== '') {
            $attributes['aria-label'] = $ariaLabel;
        }

        if ($kind === 'textarea') {
            $rows = trim($this->resolveOptionalString($item['rows'] ?? ''));

            if ($rows !== '') {
                $attributes['rows'] = $rows;
            }
        }

        if ($this->normalizeBool($item['floating'] ?? false) && in_array($kind, ['input', 'textarea'], true) && trim($this->resolveOptionalString($item['placeholder'] ?? '')) === '') {
            $attributes['placeholder'] = ' ';
        }

        if ($withDescribedby) {
            $helpId = $this->buildDescribedBy($item);

            if ($helpId !== '') {
                $attributes['aria-describedby'] = $helpId;
            }
        }

        if ($this->normalizeBool($item['readonly'] ?? false)) {
            $attributes['readonly'] = 'readonly';
        }

        if ($this->normalizeBool($item['required'] ?? false)) {
            $attributes['required'] = 'required';
        }

        if ($this->normalizeBool($item['disabled'] ?? false)) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

    /**
     * Resolves the final HTML type for an `input` control.
     *
     * @param array<string, mixed> $item
     */
    private function resolveInputType(array $item): string
    {
        $inputType = strtolower(trim($this->resolveOptionalString($item['inputType'] ?? '')));

        if ($inputType !== '') {
            return $inputType;
        }

        $type = strtolower(trim($this->resolveOptionalString($item['type'] ?? '')));

        if ($type === 'hidden') {
            return 'hidden';
        }

        if ($type === '' || in_array($type, ['input', 'textarea', 'select', 'checkbox', 'radio', 'switch', 'button', 'submit', 'reset'], true)) {
            return 'text';
        }

        return $type;
    }

    /**
     * Builds the HTML attributes for a `select` field.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildSelectAttributes(array $item): array
    {
        $classes = ['form-select'];
        $state   = $this->normalizeState($item);
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($state !== '') {
            $classes[] = 'is-' . $state;
        }

        if ($extra !== '') {
            $classes[] = $extra;
        }

        $attributes = [
            'class' => $this->normalizeClasses(implode(' ', $classes)),
        ];

        foreach (['id', 'name', 'autocomplete'] as $attribute) {
            $value = trim($this->resolveOptionalString($item[$attribute] ?? ''));

            if ($value !== '') {
                $attributes[$attribute] = $value;
            }
        }

        $ariaLabel = trim($this->resolveOptionalString($item['ariaLabel'] ?? ''));

        if ($ariaLabel !== '') {
            $attributes['aria-label'] = $ariaLabel;
        }

        $helpId = $this->buildDescribedBy($item);

        if ($helpId !== '') {
            $attributes['aria-describedby'] = $helpId;
        }

        if ($this->normalizeBool($item['required'] ?? false)) {
            $attributes['required'] = 'required';
        }

        if ($this->normalizeBool($item['disabled'] ?? false)) {
            $attributes['disabled'] = 'disabled';
        }

        if ($this->normalizeBool($item['multiple'] ?? false)) {
            $attributes['multiple'] = 'multiple';
        }

        return $attributes;
    }

    /**
     * Builds the HTML attributes for a `checkbox` or `radio` control.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildCheckAttributes(array $item, string $type): array
    {
        $classes = ['form-check-input'];
        $state   = $this->normalizeState($item);
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($state !== '') {
            $classes[] = 'is-' . $state;
        }

        if ($extra !== '') {
            $classes[] = $extra;
        }

        $attributes = [
            'class' => $this->normalizeClasses(implode(' ', $classes)),
            'type'  => $type,
        ];

        foreach (['id', 'name', 'value'] as $attribute) {
            $value = trim($this->resolveOptionalString($item[$attribute] ?? ''));

            if ($value !== '') {
                $attributes[$attribute] = $value;
            }
        }

        $describedBy = $this->buildDescribedBy($item);

        if ($describedBy !== '') {
            $attributes['aria-describedby'] = $describedBy;
        }

        foreach (['checked', 'required', 'disabled'] as $booleanAttribute) {
            if ($this->normalizeBool($item[$booleanAttribute] ?? false)) {
                $attributes[$booleanAttribute] = $booleanAttribute;
            }
        }

        return $attributes;
    }

    /**
     * Chooses the Bootstrap control class for the configured input type.
     *
     * @param array<string, mixed> $item
     */
    private function resolveControlClass(array $item): string
    {
        $type = strtolower(trim($this->resolveOptionalString($item['inputType'] ?? $item['type'] ?? 'text')));

        return $type === 'color' ? 'form-control form-control-color' : 'form-control';
    }

    /**
     * Restricts the validation state to `valid`, `invalid`, or an empty string.
     *
     * @param array<string, mixed> $item
     */
    private function normalizeState(array $item): string
    {
        $state = strtolower(trim($this->resolveOptionalString($item['state'] ?? '')));

        return in_array($state, ['valid', 'invalid'], true) ? $state : '';
    }

    /**
     * Aggregates the ARIA ids linked to help and validation feedback.
     *
     * @param array<string, mixed> $item
     */
    private function buildDescribedBy(array $item): string
    {
        $ids = [];

        foreach ([$this->resolveHelpId($item), $this->resolveFeedbackId($item, 'valid'), $this->resolveFeedbackId($item, 'invalid')] as $id) {
            if ($id !== '') {
                $ids[] = $id;
            }
        }

        return implode(' ', $ids);
    }

    /**
     * Generates the help id from the field id.
     *
     * @param array<string, mixed> $item
     */
    private function resolveHelpId(array $item): string
    {
        $help = trim($this->resolveOptionalString($item['help'] ?? ''));
        $id   = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($help === '' || $id === '') {
            return '';
        }

        return $id . 'Help';
    }

    /**
     * Generates the feedback id for the requested feedback type.
     *
     * @param array<string, mixed> $item
     */
    private function resolveFeedbackId(array $item, string $type): string
    {
        $key      = $type === 'valid' ? 'validFeedback' : 'invalidFeedback';
        $feedback = trim($this->resolveOptionalString($item[$key] ?? ''));
        $id       = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($feedback === '' || $id === '') {
            return '';
        }

        return $id . ucfirst($type) . 'Feedback';
    }

    /**
     * Normalizes the options of a `select` field.
     *
     * @return list<array<string, string>>
     */
    private function normalizeOptions(mixed $options, bool $escape): array
    {
        if (! is_array($options)) {
            return [];
        }

        $normalized = [];

        foreach ($options as $option) {
            if (is_object($option)) {
                $option = get_object_vars($option);
            }

            if (is_array($option)) {
                $label    = $this->resolveOptionalString($option['label'] ?? $option['text'] ?? $option['value'] ?? '');
                $value    = $this->resolveOptionalString($option['value'] ?? $label);
                $selected = $this->normalizeBool($option['selected'] ?? false);
            } else {
                $label    = $this->resolveOptionalString($option);
                $value    = $label;
                $selected = false;
            }

            if ($label === '') {
                continue;
            }

            $normalized[] = [
                'value'    => $value,
                'html'     => $escape ? esc($label) : $label,
                'selected' => $selected ? 'selected' : '',
            ];
        }

        return $normalized;
    }

    /**
     * Converts a nullable value into a usable string.
     */
    private function resolveOptionalString(mixed $value): string
    {
        return $value === null ? '' : (string) $value;
    }

    /**
     * Returns a trimmed string or a fallback when the value is empty.
     */
    private function resolveNonEmptyString(mixed $value, string $default): string
    {
        $value = trim($this->resolveOptionalString($value));

        return $value === '' ? $default : $value;
    }

    /**
     * Interprets the supported boolean-like configuration values.
     */
    private function normalizeBool(mixed $value, bool $default = false): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value !== 0;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            if ($normalized === '') {
                return false;
            }

            if (in_array($normalized, ['1', 'true', 'yes', 'on'], true)) {
                return true;
            }

            if (in_array($normalized, ['0', 'false', 'no', 'off'], true)) {
                return false;
            }
        }

        return $default;
    }

    /**
     * Validates a Bootstrap variant and applies a safe fallback.
     */
    private function normalizeVariant(string $variant): string
    {
        $variant = strtolower(trim($variant));

        if ($variant === '' || preg_match('/^[a-z0-9_-]+$/', $variant) !== 1) {
            return 'primary';
        }

        return $variant;
    }

    /**
     * Compacts a CSS class string into a normalized format.
     */
    private function normalizeClasses(string $classes): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized === null ? '' : $normalized;
    }
}
