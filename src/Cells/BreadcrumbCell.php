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
 * Renders a Bootstrap 5 breadcrumb component through a CodeIgniter 4 Cell.
 */
final class BreadcrumbCell extends Cell
{
    protected string $view = 'breadcrumb';

    public mixed $items = [];

    public mixed $classes = '';

    public mixed $listClasses = '';

    public mixed $itemClasses = '';

    public mixed $linkClasses = '';

    public mixed $divider = null;

    public mixed $current = 'page';

    public mixed $label = 'breadcrumb';

    public mixed $escape = true;

    /**
     * Prepares breadcrumb attributes and items for the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'breadcrumbAttributes' => $this->buildBreadcrumbAttributes(),
            'items'             => $this->normalizeItems($escape),
            'label'             => $this->resolveNonEmptyString($this->label, 'breadcrumb'),
        ]);
    }

    /**
     * Builds the HTML attributes applied to the breadcrumb list.
     *
     * @return array<string, string>
     */
    private function buildBreadcrumbAttributes(): array
    {
        $classes = ['breadcrumb'];

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        $listClasses = $this->normalizeClasses($this->resolveOptionalString($this->listClasses));

        if ($listClasses !== '') {
            $classes[] = $listClasses;
        }

        $attributes = [
            'class' => $this->normalizeClasses(implode(' ', $classes)),
        ];

        $divider = trim($this->resolveOptionalString($this->divider));

        if ($divider !== '') {
            $attributes['style'] = '--bs-breadcrumb-divider: ' . $this->normalizeDividerStyle($divider) . ';';
        }

        return $attributes;
    }

    /**
     * Normalizes breadcrumb items into the structure expected by the view.
     *
     * @return list<array{
     *     label:string,
     *     url:string,
     *     active:bool,
     *     itemClasses:string,
     *     linkClasses:string,
     *     current:string
     * }>
     */
    private function normalizeItems(bool $escape): array
    {
        if (! is_array($this->items)) {
            return [];
        }

        $items      = [];
        $lastIndex  = array_key_last($this->items);

        foreach ($this->items as $index => $item) {
            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (! is_array($item)) {
                continue;
            }

            $label = $this->resolveOptionalString($item['label'] ?? $item['title'] ?? '');

            if ($label === '') {
                continue;
            }

            $url    = $this->resolveOptionalString($item['url'] ?? $item['href'] ?? '');
            $active = array_key_exists('active', $item)
                ? $this->normalizeBool($item['active'])
                : $index === $lastIndex;

            $itemClasses = ['breadcrumb-item'];

            if ($active) {
                $itemClasses[] = 'active';
            }

            $sharedItemClasses = $this->normalizeClasses($this->resolveOptionalString($this->itemClasses));

            if ($sharedItemClasses !== '') {
                $itemClasses[] = $sharedItemClasses;
            }

            $extraItemClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

            if ($extraItemClasses !== '') {
                $itemClasses[] = $extraItemClasses;
            }

            $linkClasses = [];
            $sharedLinkClasses = $this->normalizeClasses($this->resolveOptionalString($this->linkClasses));

            if ($sharedLinkClasses !== '') {
                $linkClasses[] = $sharedLinkClasses;
            }

            $extraLinkClasses = $this->normalizeClasses($this->resolveOptionalString($item['linkClasses'] ?? ''));

            if ($extraLinkClasses !== '') {
                $linkClasses[] = $extraLinkClasses;
            }

            $items[] = [
                'label'  => $escape ? esc($label) : $label,
                'url'    => $url,
                'active' => $active,
                'itemClasses' => $this->normalizeClasses(implode(' ', $itemClasses)),
                'linkClasses' => $this->normalizeClasses(implode(' ', $linkClasses)),
                'current' => $this->resolveNonEmptyString($item['current'] ?? $this->current, 'page'),
            ];
        }

        return $items;
    }

    /**
     * Converts a divider value into a CSS-ready breadcrumb divider expression.
     */
    private function normalizeDividerStyle(string $divider): string
    {
        if (str_starts_with($divider, 'url(') || str_starts_with($divider, 'var(') || str_starts_with($divider, '"') || str_starts_with($divider, '\'')) {
            return $divider;
        }

        $divider = str_replace(['\\', '\''], ['\\\\', '\\\''], $divider);

        return '\'' . $divider . '\'';
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
     * Compacts a CSS class string into a normalized format.
     */
    private function normalizeClasses(string $classes): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized === null ? '' : $normalized;
    }
}
