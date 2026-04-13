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
 * Renders a Bootstrap 5 dropdown component through a CodeIgniter 4 Cell.
 */
final class DropdownCell extends Cell
{
    protected string $view = 'dropdown';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $items = [];

    public mixed $variant = null;

    public mixed $type = null;

    public mixed $outline = false;

    public mixed $size = null;

    public mixed $direction = null;

    public mixed $align = null;

    public mixed $dark = false;

    public mixed $classes = '';

    public mixed $buttonClasses = '';

    public mixed $menuClasses = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the dropdown payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'wrapperClasses' => $this->buildWrapperClasses(),
            'buttonClasses'  => $this->buildButtonClasses(),
            'menuClasses'    => $this->buildMenuClasses(),
            'buttonId'       => $this->resolveButtonId(),
            'labelHtml'      => $this->renderValue($this->resolveContent(), $escape),
            'items'          => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Builds the classes applied to the dropdown wrapper.
     */
    private function buildWrapperClasses(): string
    {
        $classes = ['dropdown'];

        $direction = strtolower(trim($this->resolveOptionalString($this->direction)));

        if (in_array($direction, ['dropup', 'dropend', 'dropstart', 'dropup-center', 'dropdown-center'], true)) {
            $classes[] = $direction;
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the dropdown trigger button.
     */
    private function buildButtonClasses(): string
    {
        $variant = $this->normalizeVariant($this->resolveNonEmptyString($this->variant ?? $this->type, 'secondary'));
        $prefix  = $this->normalizeBool($this->outline) ? 'btn-outline-' : 'btn-';

        $classes = [
            'btn',
            $prefix . $variant,
            'dropdown-toggle',
        ];

        $size = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg'], true)) {
            $classes[] = 'btn-' . $size;
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->buttonClasses));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the dropdown menu.
     */
    private function buildMenuClasses(): string
    {
        $classes = ['dropdown-menu'];

        $align = strtolower(trim($this->resolveOptionalString($this->align)));

        if ($align === 'end' || $align === 'right') {
            $classes[] = 'dropdown-menu-end';
        }

        if ($this->normalizeBool($this->dark)) {
            $classes[] = 'dropdown-menu-dark';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->menuClasses));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes dropdown items into headers, dividers, text, or actions.
     *
     * @return list<array<string,mixed>>
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

            if ($this->normalizeBool($item['divider'] ?? false)) {
                $items[] = ['type' => 'divider'];

                continue;
            }

            $header = $this->resolveOptionalString($item['header'] ?? '');

            if ($header !== '') {
                $items[] = [
                    'type' => 'header',
                    'html' => $escape ? esc($header) : $header,
                ];

                continue;
            }

            $text = $this->resolveOptionalString($item['text'] ?? '');

            if ($text !== '') {
                $items[] = [
                    'type' => 'text',
                    'html' => $escape ? esc($text) : $text,
                ];

                continue;
            }

            $label = $this->resolveOptionalString($item['label'] ?? $item['title'] ?? '');

            if ($label === '') {
                continue;
            }

            $disabled = $this->normalizeBool($item['disabled'] ?? false);
            $active   = $this->normalizeBool($item['active'] ?? false);
            $url      = $this->resolveOptionalString($item['url'] ?? $item['href'] ?? '');
            $tag      = $url !== '' ? 'a' : 'button';
            $classes  = ['dropdown-item'];

            if ($active) {
                $classes[] = 'active';
            }

            if ($disabled) {
                $classes[] = 'disabled';
            }

            $extraClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

            if ($extraClasses !== '') {
                $classes[] = $extraClasses;
            }

            $attributes = [
                'class' => $this->normalizeClasses(implode(' ', $classes)),
            ];

            if ($tag === 'a') {
                if (! $disabled) {
                    $attributes['href'] = $url;
                }

                if ($disabled) {
                    $attributes['aria-disabled'] = 'true';
                    $attributes['tabindex']      = '-1';
                }
            } else {
                $attributes['type'] = $this->resolveNonEmptyString($item['buttonType'] ?? '', 'button');

                if ($disabled) {
                    $attributes['disabled']      = 'disabled';
                    $attributes['aria-disabled'] = 'true';
                }
            }

            $items[] = [
                'type'       => 'item',
                'tag'        => $tag,
                'html'       => $escape ? esc($label) : $label,
                'attributes' => $attributes,
            ];
        }

        return $items;
    }

    /**
     * Chooses the rendered trigger content from `content` or `message`.
     */
    private function resolveContent(): string
    {
        if ($this->content !== null && $this->content !== '') {
            return (string) $this->content;
        }

        return $this->message === null ? '' : (string) $this->message;
    }

    /**
     * Resolves a stable dropdown trigger id, generating one when needed.
     */
    private function resolveButtonId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'dropdown-' . substr(md5(serialize([$this->content, $this->items, $this->classes])), 0, 8);
    }

    /**
     * Renders an optional value as escaped or raw HTML.
     */
    private function renderValue(string $value, bool $escape): string
    {
        if ($value === '') {
            return '';
        }

        return $escape ? esc($value) : $value;
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
            return 'secondary';
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
