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
 * Renders a Bootstrap 5 pagination component through a CodeIgniter 4 Cell.
 */
final class PaginationCell extends Cell
{
    protected string $view = 'pagination';

    public mixed $items = [];

    public mixed $currentPage = null;

    public mixed $totalPages = null;

    public mixed $urlPattern = '?page={page}';

    public mixed $showPreviousNext = true;

    public mixed $showFirstLast = false;

    public mixed $previousLabel = '&laquo;';

    public mixed $nextLabel = '&raquo;';

    public mixed $firstLabel = '&laquo;&laquo;';

    public mixed $lastLabel = '&raquo;&raquo;';

    public mixed $window = 1;

    public mixed $size = null;

    public mixed $align = null;

    public mixed $classes = '';

    public mixed $itemClasses = '';

    public mixed $linkClasses = '';

    public mixed $label = 'Pagination navigation';

    public mixed $escape = true;

    /**
     * Prepares the pagination payload passed to the view.
     */
    public function render(): string
    {
        return $this->view($this->view, [
            'paginationClasses' => $this->buildPaginationClasses(),
            'label'             => $this->resolveNonEmptyString($this->label, 'Pagination navigation'),
            'items'             => $this->normalizeItems($this->normalizeBool($this->escape, true)),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the pagination list.
     */
    private function buildPaginationClasses(): string
    {
        $classes = ['pagination'];

        $size = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg'], true)) {
            $classes[] = 'pagination-' . $size;
        }

        $align = strtolower(trim($this->resolveOptionalString($this->align)));

        if ($align === 'center') {
            $classes[] = 'justify-content-center';
        } elseif ($align === 'end' || $align === 'right') {
            $classes[] = 'justify-content-end';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes manual or generated pagination items for the view.
     *
     * @return list<array{
     *     itemClasses:string,
     *     linkTag:string,
     *     linkClasses:string,
     *     linkAttributes:array<string,string>,
     *     labelHtml:string,
     *     separator:bool
     * }>
     */
    private function normalizeItems(bool $escape): array
    {
        if (is_array($this->items) && $this->items !== []) {
            return $this->normalizeManualItems($escape);
        }

        return $this->normalizeGeneratedItems($escape);
    }

    /**
     * Normalizes manually provided pagination items.
     *
     * @return list<array{
     *     itemClasses:string,
     *     linkTag:string,
     *     linkClasses:string,
     *     linkAttributes:array<string,string>,
     *     labelHtml:string,
     *     separator:bool
     * }>
     */
    private function normalizeManualItems(bool $escape): array
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

            $separator = $this->normalizeBool($item['ellipsis'] ?? false);
            $rawLabel  = $this->normalizeBool($item['rawLabel'] ?? false);
            $label = $this->resolveOptionalString($item['label'] ?? ($separator ? '&hellip;' : ''));

            if ($label === '') {
                continue;
            }

            $active   = $this->normalizeBool($item['active'] ?? false);
            $disabled = $this->normalizeBool($item['disabled'] ?? false);
            $url      = $this->resolveOptionalString($item['url'] ?? $item['href'] ?? '');

            $itemClasses = ['page-item'];

            if ($active) {
                $itemClasses[] = 'active';
            }

            if ($disabled) {
                $itemClasses[] = 'disabled';
            }

            $extraItemClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

            if ($extraItemClasses !== '') {
                $itemClasses[] = $extraItemClasses;
            }

            $sharedItemClasses = $this->normalizeClasses($this->resolveOptionalString($this->itemClasses));

            if ($sharedItemClasses !== '') {
                $itemClasses[] = $sharedItemClasses;
            }

            $linkTag        = ($url !== '' && ! $active && ! $disabled && ! $separator) ? 'a' : 'span';
            $linkAttributes = [];

            if ($linkTag === 'a') {
                $linkAttributes['href'] = $url;
            }

            $ariaLabel = $this->resolveOptionalString($item['ariaLabel'] ?? '');

            if ($ariaLabel !== '') {
                $linkAttributes['aria-label'] = $ariaLabel;
            }

            if ($active) {
                $linkAttributes['aria-current'] = 'page';
            }

            if ($separator) {
                $linkAttributes['aria-hidden'] = 'true';
            }

            $linkClasses = ['page-link'];
            $sharedLinkClasses = $this->normalizeClasses($this->resolveOptionalString($this->linkClasses));

            if ($sharedLinkClasses !== '') {
                $linkClasses[] = $sharedLinkClasses;
            }

            $extraLinkClasses = $this->normalizeClasses($this->resolveOptionalString($item['linkClasses'] ?? ''));

            if ($extraLinkClasses !== '') {
                $linkClasses[] = $extraLinkClasses;
            }

            $items[] = [
                'itemClasses'    => $this->normalizeClasses(implode(' ', $itemClasses)),
                'linkTag'        => $linkTag,
                'linkClasses'    => $this->normalizeClasses(implode(' ', $linkClasses)),
                'linkAttributes' => $linkAttributes,
                'labelHtml'      => ($separator || $rawLabel) && $escape ? $label : ($escape ? esc($label) : $label),
                'separator'      => $separator,
            ];
        }

        return $items;
    }

    /**
     * Generates pagination items from page numbers and display options.
     *
     * @return list<array{
     *     itemClasses:string,
     *     linkTag:string,
     *     linkClasses:string,
     *     linkAttributes:array<string,string>,
     *     labelHtml:string,
     *     separator:bool
     * }>
     */
    private function normalizeGeneratedItems(bool $escape): array
    {
        $currentPage = $this->normalizePositiveInt($this->currentPage);
        $totalPages  = $this->normalizePositiveInt($this->totalPages);

        if ($currentPage < 1 || $totalPages < 1) {
            return [];
        }

        $currentPage = min($currentPage, $totalPages);
        $window      = max(0, $this->normalizePositiveInt($this->window, 1));
        $pages       = [1, $totalPages];

        for ($page = $currentPage - $window; $page <= $currentPage + $window; $page++) {
            if ($page >= 1 && $page <= $totalPages) {
                $pages[] = $page;
            }
        }

        $pages = array_values(array_unique($pages));
        sort($pages);

        $items = [];

        if ($this->normalizeBool($this->showFirstLast) && $currentPage > 1) {
            $items[] = $this->createGeneratedItem(1, $this->resolveOptionalString($this->firstLabel), false, false, 'First', $escape);
        }

        if ($this->normalizeBool($this->showPreviousNext)) {
            $items[] = $this->createGeneratedItem(
                max(1, $currentPage - 1),
                $this->resolveOptionalString($this->previousLabel),
                false,
                $currentPage <= 1,
                'Previous',
                $escape
            );
        }

        $previousPage = null;

        foreach ($pages as $page) {
            if ($previousPage !== null && $page > $previousPage + 1) {
                $items[] = $this->createManualItem([
                    'label'    => '&hellip;',
                    'ellipsis' => true,
                ], false);
            }

            $items[] = $this->createGeneratedItem($page, (string) $page, $page === $currentPage, false, 'Page ' . $page, true);
            $previousPage = $page;
        }

        if ($this->normalizeBool($this->showPreviousNext)) {
            $items[] = $this->createGeneratedItem(
                min($totalPages, $currentPage + 1),
                $this->resolveOptionalString($this->nextLabel),
                false,
                $currentPage >= $totalPages,
                'Next',
                $escape
            );
        }

        if ($this->normalizeBool($this->showFirstLast) && $currentPage < $totalPages) {
            $items[] = $this->createGeneratedItem($totalPages, $this->resolveOptionalString($this->lastLabel), false, false, 'Last', $escape);
        }

        return $items;
    }

    /**
     * Builds a generated pagination item and delegates its normalization.
     *
     * @return array{
     *     itemClasses:string,
     *     linkTag:string,
     *     linkClasses:string,
     *     linkAttributes:array<string,string>,
     *     labelHtml:string,
     *     separator:bool
     * }
     */
    private function createGeneratedItem(int $page, string $label, bool $active, bool $disabled, string $ariaLabel, bool $escape): array
    {
        return $this->createManualItem([
            'label'     => $label,
            'url'       => $this->resolvePageUrl($page),
            'active'    => $active,
            'disabled'  => $disabled,
            'ariaLabel' => $ariaLabel,
            'rawLabel'  => preg_match('/&[a-z0-9#]+;/', $label) === 1,
        ], $escape);
    }

    /**
     * Normalizes a single manual pagination item.
     *
     * @return array{
     *     itemClasses:string,
     *     linkTag:string,
     *     linkClasses:string,
     *     linkAttributes:array<string,string>,
     *     labelHtml:string,
     *     separator:bool
     * }
     */
    private function createManualItem(array $item, bool $escape): array
    {
        $originalItems = $this->items;
        $this->items   = [$item];
        $normalized    = $this->normalizeManualItems($escape);
        $this->items   = $originalItems;

        return $normalized[0];
    }

    /**
     * Resolves the target URL for a generated page item.
     */
    private function resolvePageUrl(int $page): string
    {
        $pattern = $this->resolveOptionalString($this->urlPattern);

        if ($pattern === '') {
            return '?page=' . $page;
        }

        if (str_contains($pattern, '{page}')) {
            return str_replace('{page}', (string) $page, $pattern);
        }

        return rtrim($pattern, '/') . '/' . $page;
    }

    /**
     * Normalizes a positive integer configuration value.
     */
    private function normalizePositiveInt(mixed $value, int $default = 0): int
    {
        if (is_int($value)) {
            return $value > 0 ? $value : $default;
        }

        if (is_string($value) && preg_match('/^\d+$/', trim($value)) === 1) {
            $normalized = (int) trim($value);

            return $normalized > 0 ? $normalized : $default;
        }

        return $default;
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
