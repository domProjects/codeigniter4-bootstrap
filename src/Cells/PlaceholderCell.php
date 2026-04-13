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
 * Renders Bootstrap 5 placeholder blocks through a CodeIgniter 4 Cell.
 */
final class PlaceholderCell extends Cell
{
    protected string $view = 'placeholder';

    public mixed $items = [];

    public mixed $width = 12;

    public mixed $variant = null;

    public mixed $size = null;

    public mixed $animation = null;

    public mixed $classes = '';

    public mixed $itemClasses = '';

    /**
     * Prepares the placeholder payload passed to the view.
     */
    public function render(): string
    {
        return $this->view($this->view, [
            'wrapperClasses' => $this->buildWrapperClasses(),
            'items'          => $this->normalizeItems(),
        ]);
    }

    /**
     * Builds the classes applied to the placeholder wrapper.
     */
    private function buildWrapperClasses(): string
    {
        $classes = [];
        $animation = strtolower(trim($this->resolveOptionalString($this->animation)));

        if (in_array($animation, ['glow', 'wave'], true)) {
            $classes[] = 'placeholder-' . $animation;
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes placeholder items into class strings ready for rendering.
     *
     * @return list<string>
     */
    private function normalizeItems(): array
    {
        if (is_array($this->items) && $this->items !== []) {
            $items = [];

            foreach ($this->items as $item) {
                if (is_object($item)) {
                    $item = get_object_vars($item);
                }

                if (! is_array($item)) {
                    continue;
                }

                $items[] = $this->buildPlaceholderClasses(
                    $item['width'] ?? 12,
                    $item['variant'] ?? null,
                    $item['size'] ?? null,
                    $item['classes'] ?? '',
                );
            }

            return $items;
        }

        return [
            $this->buildPlaceholderClasses($this->width, $this->variant, $this->size, $this->itemClasses),
        ];
    }

    /**
     * Builds the class list for a single placeholder block.
     */
    private function buildPlaceholderClasses(mixed $width, mixed $variant, mixed $size, mixed $classes): string
    {
        $items = ['placeholder', 'd-block'];

        $widthString = trim($this->resolveOptionalString($width));

        if ($widthString !== '') {
            if (ctype_digit($widthString)) {
                $items[] = 'col-' . min(12, max(1, (int) $widthString));
            } else {
                $items[] = $widthString;
            }
        }

        $variantString = strtolower(trim($this->resolveOptionalString($variant)));

        if ($variantString !== '' && preg_match('/^[a-z0-9_-]+$/', $variantString) === 1) {
            $items[] = 'bg-' . $variantString;
        }

        $sizeString = strtolower(trim($this->resolveOptionalString($size)));

        if (in_array($sizeString, ['lg', 'sm', 'xs'], true)) {
            $items[] = 'placeholder-' . $sizeString;
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($classes));

        if ($extra !== '') {
            $items[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $items));
    }

    /**
     * Converts a nullable value into a usable string.
     */
    private function resolveOptionalString(mixed $value): string
    {
        return $value === null ? '' : (string) $value;
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
