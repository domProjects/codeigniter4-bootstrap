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
 * Renders a Bootstrap 5 input group component through a CodeIgniter 4 Cell.
 */
final class InputGroupCell extends Cell
{
    protected string $view = 'input_group';

    public mixed $items = [];

    public mixed $size = null;

    public mixed $classes = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the input group payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'attributes' => $this->buildAttributes(),
            'items'      => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Builds the HTML attributes for the root container.
     *
     * @return array<string, string>
     */
    private function buildAttributes(): array
    {
        $attributes = [
            'class' => $this->buildGroupClasses(),
        ];

        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        return $attributes;
    }

    /**
     * Builds the Bootstrap classes for the input group container.
     */
    private function buildGroupClasses(): string
    {
        $classes = ['input-group'];

        if ($this->hasValidation()) {
            $classes[] = 'has-validation';
        }

        $size    = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg'], true)) {
            $classes[] = 'input-group-' . $size;
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Detects whether any item uses Bootstrap validation styling.
     */
    private function hasValidation(): bool
    {
        if (! is_array($this->items)) {
            return false;
        }

        foreach ($this->items as $item) {
            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (! is_array($item)) {
                continue;
            }

            if (
                $this->resolveOptionalString($item['validFeedback'] ?? '') !== ''
                || $this->resolveOptionalString($item['invalidFeedback'] ?? '') !== ''
                || $this->normalizeState($item) !== ''
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalizes input-group items into a common render structure.
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

            $kind = strtolower(trim($this->resolveOptionalString($item['type'] ?? 'text')));

            if (in_array($kind, ['text', 'addon', 'label'], true)) {
                $content = $this->resolveOptionalString($item['content'] ?? $item['text'] ?? $item['label'] ?? '');

                if ($content === '') {
                    continue;
                }

                $items[] = [
                    'kind'       => 'addon',
                    'tag'        => 'span',
                    'attributes' => [
                        'class' => $this->buildAddonClasses($item),
                    ],
                    'html'       => $escape ? esc($content) : $content,
                ];

                continue;
            }

            if (in_array($kind, ['checkbox', 'radio'], true)) {
                $inputClasses = ['form-check-input', 'mt-0'];
                $state        = $this->normalizeState($item);
                $extraClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

                if ($state !== '') {
                    $inputClasses[] = 'is-' . $state;
                }

                if ($extraClasses !== '') {
                    $inputClasses[] = $extraClasses;
                }

                $attributes = [
                    'class' => $this->normalizeClasses(implode(' ', $inputClasses)),
                    'type'  => $kind,
                    'value' => $this->resolveOptionalString($item['value'] ?? ''),
                ];

                foreach (['id', 'name'] as $attribute) {
                    $value = trim($this->resolveOptionalString($item[$attribute] ?? ''));

                    if ($value !== '') {
                        $attributes[$attribute] = $value;
                    }
                }

                $ariaLabel = trim($this->resolveOptionalString($item['ariaLabel'] ?? ''));

                if ($ariaLabel !== '') {
                    $attributes['aria-label'] = $ariaLabel;
                }

                $describedBy = $this->buildDescribedBy($item);

                if ($describedBy !== '') {
                    $attributes['aria-describedby'] = $describedBy;
                }

                if ($this->normalizeBool($item['checked'] ?? false)) {
                    $attributes['checked'] = 'checked';
                }

                if ($this->normalizeBool($item['disabled'] ?? false)) {
                    $attributes['disabled'] = 'disabled';
                }

                $items[] = [
                    'kind'              => 'check',
                    'wrapperAttributes' => ['class' => 'input-group-text'],
                    'tag'               => 'input',
                    'attributes'        => $attributes,
                    'labelHtml'         => '',
                    'wrapperClasses'    => '',
                    'validFeedbackHtml' => $this->renderOptionalHtml($item['validFeedback'] ?? '', $escape),
                    'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
                    'invalidFeedbackHtml' => $this->renderOptionalHtml($item['invalidFeedback'] ?? '', $escape),
                    'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
                ];

                continue;
            }

            if ($kind === 'button') {
                $label = $this->resolveOptionalString($item['label'] ?? $item['content'] ?? $item['text'] ?? '');

                if ($label === '') {
                    continue;
                }

                $items[] = [
                    'kind'       => 'button',
                    'tag'        => 'button',
                    'html'       => $escape ? esc($label) : $label,
                    'attributes' => $this->buildButtonAttributes($item),
                ];

                continue;
            }

            if ($kind === 'select') {
                $items[] = [
                    'kind'       => 'select',
                    'tag'        => 'select',
                    'attributes' => $this->buildSelectAttributes($item),
                    'options'    => $this->normalizeOptions($item['options'] ?? [], $escape),
                    'wrapperClasses' => $this->buildControlWrapperClasses($item),
                    'labelHtml' => $this->renderOptionalHtml($item['label'] ?? '', $escape),
                    'labelAttributes' => $this->buildLabelAttributes($item),
                    'validFeedbackHtml' => $this->renderOptionalHtml($item['validFeedback'] ?? '', $escape),
                    'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
                    'invalidFeedbackHtml' => $this->renderOptionalHtml($item['invalidFeedback'] ?? '', $escape),
                    'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
                ];

                continue;
            }

            if ($kind === 'textarea') {
                $value = $this->resolveOptionalString($item['value'] ?? $item['content'] ?? '');

                $items[] = [
                    'kind'       => 'textarea',
                    'tag'        => 'textarea',
                    'html'       => $escape ? esc($value) : $value,
                    'attributes' => $this->buildControlAttributes($item, 'textarea'),
                    'wrapperClasses' => $this->buildControlWrapperClasses($item),
                    'labelHtml' => $this->renderOptionalHtml($item['label'] ?? '', $escape),
                    'labelAttributes' => $this->buildLabelAttributes($item),
                    'validFeedbackHtml' => $this->renderOptionalHtml($item['validFeedback'] ?? '', $escape),
                    'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
                    'invalidFeedbackHtml' => $this->renderOptionalHtml($item['invalidFeedback'] ?? '', $escape),
                    'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
                ];

                continue;
            }

            $items[] = [
                'kind'       => 'input',
                'tag'        => 'input',
                'attributes' => $this->buildControlAttributes($item, 'input'),
                'wrapperClasses' => $this->buildControlWrapperClasses($item),
                'labelHtml' => $this->renderOptionalHtml($item['label'] ?? '', $escape),
                'labelAttributes' => $this->buildLabelAttributes($item),
                'validFeedbackHtml' => $this->renderOptionalHtml($item['validFeedback'] ?? '', $escape),
                'validFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'valid'),
                'invalidFeedbackHtml' => $this->renderOptionalHtml($item['invalidFeedback'] ?? '', $escape),
                'invalidFeedbackAttributes' => $this->buildFeedbackAttributes($item, 'invalid'),
            ];
        }

        return $items;
    }

    /**
     * Builds the classes for a text addon item.
     *
     * @param array<string, mixed> $item
     */
    private function buildAddonClasses(array $item): string
    {
        $classes = ['input-group-text'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the wrapper used for floating controls when needed.
     *
     * @param array<string, mixed> $item
     */
    private function buildControlWrapperClasses(array $item): string
    {
        if (! $this->normalizeBool($item['floating'] ?? false)) {
            return '';
        }

        $classes = ['form-floating'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['wrapperClasses'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the attributes for a floating control label.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildLabelAttributes(array $item): array
    {
        $attributes = [];
        $for = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($for !== '') {
            $attributes['for'] = $for;
        }

        $classes = $this->normalizeClasses($this->resolveOptionalString($item['labelClasses'] ?? ''));

        if ($classes !== '') {
            $attributes['class'] = $classes;
        }

        return $attributes;
    }

    /**
     * Builds the attributes for validation feedback blocks.
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
     * Builds the HTML attributes for a button inside the group.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildButtonAttributes(array $item): array
    {
        $variant = $this->normalizeVariant($this->resolveNonEmptyString($item['variant'] ?? '', 'outline-secondary'));
        $classes = ['btn', 'btn-' . $variant];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        $attributes = [
            'class' => $this->normalizeClasses(implode(' ', $classes)),
            'type'  => $this->resolveNonEmptyString($item['buttonType'] ?? '', 'button'),
        ];

        $id = trim($this->resolveOptionalString($item['id'] ?? ''));

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        if ($this->normalizeBool($item['disabled'] ?? false)) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

    /**
     * Builds the HTML attributes for an input or textarea control.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    private function buildControlAttributes(array $item, string $kind): array
    {
        $classes = ['form-control'];
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

        foreach (['id', 'name', 'placeholder', 'value'] as $attribute) {
            $value = trim($this->resolveOptionalString($item[$attribute] ?? ''));

            if ($value !== '') {
                $attributes[$attribute] = $value;
            }
        }

        $ariaLabel = trim($this->resolveOptionalString($item['ariaLabel'] ?? ''));

        if ($ariaLabel !== '') {
            $attributes['aria-label'] = $ariaLabel;
        }

        $ariaDescribedby = trim($this->resolveOptionalString($item['ariaDescribedby'] ?? ''));

        if ($ariaDescribedby !== '') {
            $attributes['aria-describedby'] = $ariaDescribedby;
        }

        $describedBy = $this->buildDescribedBy($item);

        if ($describedBy !== '') {
            $attributes['aria-describedby'] = $describedBy;
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

        if ($this->normalizeBool($item['readonly'] ?? false)) {
            $attributes['readonly'] = 'readonly';
        }

        if ($this->normalizeBool($item['disabled'] ?? false)) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

    /**
     * Resolves the final HTML type for an input-group `input`.
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

        if ($type === '' || in_array($type, ['input', 'text', 'addon', 'label', 'button', 'select', 'textarea', 'checkbox', 'radio'], true)) {
            return 'text';
        }

        return $type;
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
     * Aggregates ARIA references tied to validation feedback.
     *
     * @param array<string, mixed> $item
     */
    private function buildDescribedBy(array $item): string
    {
        $ids = [];
        $raw = trim($this->resolveOptionalString($item['ariaDescribedby'] ?? ''));

        if ($raw !== '') {
            $ids[] = $raw;
        }

        foreach ([$this->resolveFeedbackId($item, 'valid'), $this->resolveFeedbackId($item, 'invalid')] as $id) {
            if ($id !== '') {
                $ids[] = $id;
            }
        }

        return implode(' ', $ids);
    }

    /**
     * Generates the feedback id associated with a control.
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
     * Builds the HTML attributes for a `select` inside an input group.
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

        foreach (['id', 'name'] as $attribute) {
            $value = trim($this->resolveOptionalString($item[$attribute] ?? ''));

            if ($value !== '') {
                $attributes[$attribute] = $value;
            }
        }

        $ariaLabel = trim($this->resolveOptionalString($item['ariaLabel'] ?? ''));

        if ($ariaLabel !== '') {
            $attributes['aria-label'] = $ariaLabel;
        }

        $describedBy = $this->buildDescribedBy($item);

        if ($describedBy !== '') {
            $attributes['aria-describedby'] = $describedBy;
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
     * Renders an optional value as escaped or raw HTML.
     */
    private function renderOptionalHtml(mixed $value, bool $escape): string
    {
        $content = $this->resolveOptionalString($value);

        if ($content === '') {
            return '';
        }

        return $escape ? esc($content) : $content;
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
            return 'outline-secondary';
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
