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
 * Renders a Bootstrap 5 button group component through a CodeIgniter 4 Cell.
 */
final class ButtonGroupCell extends Cell
{
    protected string $view = 'button_group';

    public mixed $items = [];

    public mixed $vertical = false;

    public mixed $size = null;

    public mixed $label = 'Button group';

    public mixed $role = 'group';

    public mixed $classes = '';

    public mixed $escape = true;

    /**
     * Prepares the button group payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'wrapperAttributes' => $this->buildWrapperAttributes(),
            'items'             => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Builds the wrapper attributes for the group container.
     *
     * @return array<string, string>
     */
    private function buildWrapperAttributes(): array
    {
        $classes = [
            $this->normalizeBool($this->vertical) ? 'btn-group-vertical' : 'btn-group',
        ];

        $size = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg'], true)) {
            $classes[] = 'btn-group-' . $size;
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return [
            'class'      => $this->normalizeClasses(implode(' ', $classes)),
            'role'       => $this->resolveNonEmptyString($this->role, 'group'),
            'aria-label' => $this->resolveNonEmptyString($this->label, 'Button group'),
        ];
    }

    /**
     * Normalizes the group items into renderable buttons or links.
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

            $content = $this->resolveOptionalString($item['content'] ?? $item['message'] ?? $item['label'] ?? '');

            if ($content === '') {
                continue;
            }

            $tag      = $this->resolveTag($item);
            $disabled = $this->normalizeBool($item['disabled'] ?? false);
            $active   = $this->normalizeBool($item['active'] ?? false);
            $href     = $this->resolveOptionalString($item['href'] ?? $item['url'] ?? '');
            $role     = trim($this->resolveOptionalString($item['role'] ?? ''));

            if ($tag === 'a' && $role === '') {
                $role = 'button';
            }

            $attributes = [
                'class' => $this->buildButtonClasses($item, $active, $disabled, $tag),
            ];

            if ($tag === 'a') {
                if ($href !== '' && ! $disabled) {
                    $attributes['href'] = $href;
                }

                if ($role !== '') {
                    $attributes['role'] = $role;
                }

                if ($disabled) {
                    $attributes['aria-disabled'] = 'true';
                    $attributes['tabindex']      = '-1';
                }
            } else {
                $attributes['type'] = $this->resolveNonEmptyString($item['buttonType'] ?? '', 'button');

                if ($disabled) {
                    $attributes['disabled'] = 'disabled';
                }

                if ($active) {
                    $attributes['aria-pressed'] = 'true';
                }
            }

            $items[] = [
                'tag'        => $tag,
                'attributes' => $attributes,
                'html'       => $escape ? esc($content) : $content,
            ];
        }

        return $items;
    }

    /**
     * Builds the classes applied to an individual grouped button.
     *
     * @param array<string, mixed> $item
     */
    private function buildButtonClasses(array $item, bool $active, bool $disabled, string $tag): string
    {
        $variant = $this->normalizeVariant($this->resolveNonEmptyString($item['variant'] ?? $item['type'] ?? '', 'primary'));
        $prefix  = $this->normalizeBool($item['outline'] ?? false) ? 'btn-outline-' : 'btn-';
        $classes = [
            'btn',
            $prefix . $variant,
        ];

        if ($active) {
            $classes[] = 'active';
        }

        if ($disabled && $tag === 'a') {
            $classes[] = 'disabled';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves whether a grouped item renders as a link or a button.
     *
     * @param array<string, mixed> $item
     */
    private function resolveTag(array $item): string
    {
        $tag = strtolower(trim($this->resolveOptionalString($item['tag'] ?? '')));

        if ($tag === '') {
            return $this->resolveOptionalString($item['href'] ?? $item['url'] ?? '') !== '' ? 'a' : 'button';
        }

        return in_array($tag, ['a', 'button'], true) ? $tag : 'button';
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
