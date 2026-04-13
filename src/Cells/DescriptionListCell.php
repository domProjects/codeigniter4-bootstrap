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
 * Renders a Bootstrap-friendly description list through a CodeIgniter 4 Cell.
 */
final class DescriptionListCell extends Cell
{
    protected string $view = 'description_list';

    public mixed $items = [];

    public mixed $classes = '';

    public mixed $row = true;

    public mixed $termClasses = 'col-sm-3';

    public mixed $descriptionClasses = 'col-sm-9';

    public mixed $escape = true;

    /**
     * Prepares the description list payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'listClasses' => $this->buildListClasses(),
            'items'       => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Builds the classes applied to the `dl` container.
     */
    private function buildListClasses(): string
    {
        $classes = [];

        if ($this->normalizeBool($this->row)) {
            $classes[] = 'row';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes term and description pairs into the structure expected by the view.
     *
     * @return list<array{termHtml:string,descriptionHtml:string,termClasses:string,descriptionClasses:string}>
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

            $term        = $this->resolveOptionalString($item['term'] ?? $item['label'] ?? '');
            $description = $this->resolveOptionalString($item['description'] ?? $item['content'] ?? $item['value'] ?? '');

            if ($term === '' && $description === '') {
                continue;
            }

            $items[] = [
                'termHtml'          => $escape ? esc($term) : $term,
                'descriptionHtml'   => $escape ? esc($description) : $description,
                'termClasses'       => $this->normalizeClasses($this->resolveOptionalString($item['termClasses'] ?? $this->termClasses)),
                'descriptionClasses'=> $this->normalizeClasses($this->resolveOptionalString($item['descriptionClasses'] ?? $this->descriptionClasses)),
            ];
        }

        return $items;
    }

    /**
     * Converts a nullable value into a usable string.
     */
    private function resolveOptionalString(mixed $value): string
    {
        return $value === null ? '' : (string) $value;
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
