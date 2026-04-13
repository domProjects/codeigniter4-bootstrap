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
 * Renders a Bootstrap 5 list group component through a CodeIgniter 4 Cell.
 */
final class ListGroupCell extends Cell
{
    protected string $view = 'list_group';

    public mixed $items = [];

    public mixed $flush = false;

    public mixed $numbered = false;

    public mixed $horizontal = null;

    public mixed $classes = '';

    public mixed $escape = true;

    /**
     * Prepares the list group payload passed to the view.
     */
    public function render(): string
    {
        $interactive = $this->hasInteractiveItems();

        return $this->view($this->view, [
            'groupTag'     => $interactive ? 'div' : ($this->normalizeBool($this->numbered) ? 'ol' : 'ul'),
            'groupClasses' => $this->buildGroupClasses(),
            'items'        => $this->normalizeItems($interactive, $this->normalizeBool($this->escape, true)),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the list group wrapper.
     */
    private function buildGroupClasses(): string
    {
        $classes = ['list-group'];

        if ($this->normalizeBool($this->flush)) {
            $classes[] = 'list-group-flush';
        }

        if ($this->normalizeBool($this->numbered)) {
            $classes[] = 'list-group-numbered';
        }

        $horizontal = strtolower(trim($this->resolveOptionalString($this->horizontal)));

        if ($horizontal !== '') {
            $classes[] = $horizontal === 'true' || $horizontal === '1'
                ? 'list-group-horizontal'
                : 'list-group-horizontal-' . $horizontal;
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes list-group items into the structure expected by the view.
     *
     * @return list<array{
     *     tag:string,
     *     attributes:array<string,string>,
     *     labelHtml:string,
     *     badgeHtml:string,
     *     badgeClasses:string
     * }>
     */
    private function normalizeItems(bool $interactive, bool $escape): array
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

            $label = $this->resolveOptionalString($item['label'] ?? $item['title'] ?? '');

            if ($label === '') {
                continue;
            }

            $active       = $this->normalizeBool($item['active'] ?? false);
            $disabled     = $this->normalizeBool($item['disabled'] ?? false);
            $variant      = $this->normalizeVariant($this->resolveOptionalString($item['variant'] ?? ''));
            $badge        = $this->resolveOptionalString($item['badge'] ?? '');
            $badgeVariant = $this->normalizeVariant($this->resolveNonEmptyString($item['badgeVariant'] ?? '', 'primary'));
            $itemClasses  = ['list-group-item'];

            if ($interactive) {
                $itemClasses[] = 'list-group-item-action';
            }

            if ($active) {
                $itemClasses[] = 'active';
            }

            if ($disabled) {
                $itemClasses[] = 'disabled';
            }

            if ($variant !== '') {
                $itemClasses[] = 'list-group-item-' . $variant;
            }

            if ($badge !== '') {
                $itemClasses[] = 'd-flex';
                $itemClasses[] = 'justify-content-between';
                $itemClasses[] = 'align-items-center';
            }

            $extraClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

            if ($extraClasses !== '') {
                $itemClasses[] = $extraClasses;
            }

            $tag        = 'li';
            $attributes = [
                'class' => $this->normalizeClasses(implode(' ', $itemClasses)),
            ];

            if ($interactive) {
                $url = $this->resolveOptionalString($item['url'] ?? $item['href'] ?? '');

                if ($url !== '') {
                    $tag = 'a';

                    if (! $disabled) {
                        $attributes['href'] = $url;
                    }

                    if ($disabled) {
                        $attributes['aria-disabled'] = 'true';
                        $attributes['tabindex']      = '-1';
                    }
                } else {
                    $tag                = 'button';
                    $attributes['type'] = $this->resolveNonEmptyString($item['buttonType'] ?? '', 'button');

                    if ($disabled) {
                        $attributes['disabled']      = 'disabled';
                        $attributes['aria-disabled'] = 'true';
                    }
                }
            }

            if ($active) {
                $attributes['aria-current'] = 'true';
            }

            $items[] = [
                'tag'          => $tag,
                'attributes'   => $attributes,
                'labelHtml'    => $escape ? esc($label) : $label,
                'badgeHtml'    => $badge === '' ? '' : ($escape ? esc($badge) : $badge),
                'badgeClasses' => $badge === '' ? '' : 'badge text-bg-' . $badgeVariant . ' rounded-pill',
            ];
        }

        return $items;
    }

    /**
     * Detects whether any item should render as an interactive element.
     */
    private function hasInteractiveItems(): bool
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
                $this->resolveOptionalString($item['url'] ?? $item['href'] ?? '') !== ''
                || strtolower(trim($this->resolveOptionalString($item['tag'] ?? ''))) === 'button'
            ) {
                return true;
            }
        }

        return false;
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
     * Validates a Bootstrap variant or returns an empty string.
     */
    private function normalizeVariant(string $variant): string
    {
        $variant = strtolower(trim($variant));

        if ($variant === '') {
            return '';
        }

        if (preg_match('/^[a-z0-9_-]+$/', $variant) !== 1) {
            return '';
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
