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
 * Renders a grid of Bootstrap stat cards through a CodeIgniter 4 Cell.
 */
final class StatsCardsCell extends Cell
{
    protected string $view = 'stats_cards';

    public mixed $items = [];

    public mixed $classes = 'row g-3';

    public mixed $columnClasses = 'col-md-6 col-xl-3';

    public mixed $cardClasses = 'card h-100 shadow-sm border-0';

    public mixed $escape = true;

    /**
     * Prepares the stats-card grid payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'wrapperClasses' => $this->normalizeClasses($this->resolveOptionalString($this->classes)),
            'items'          => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Normalizes stat cards and their visual variants.
     *
     * @return list<array<string, string>>
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

            $value = $this->resolveOptionalString($item['value'] ?? $item['number'] ?? '');
            $label = $this->resolveOptionalString($item['label'] ?? $item['title'] ?? '');

            if ($value === '' && $label === '') {
                continue;
            }

            $variant = $this->normalizeVariant($this->resolveOptionalString($item['variant'] ?? ''));
            $cardClasses = [$this->normalizeClasses($this->resolveOptionalString($item['cardClasses'] ?? $this->cardClasses))];

            if ($variant !== '') {
                $cardClasses[] = 'text-bg-' . $variant;
            }

            $items[] = [
                'columnClasses'  => $this->normalizeClasses($this->resolveOptionalString($item['columnClasses'] ?? $this->columnClasses)),
                'cardClasses'    => $this->normalizeClasses(implode(' ', array_filter($cardClasses))),
                'labelHtml'      => $escape ? esc($label) : $label,
                'valueHtml'      => $escape ? esc($value) : $value,
                'descriptionHtml'=> $this->renderOptionalHtml($item['description'] ?? '', $escape),
                'metaHtml'       => $this->renderOptionalHtml($item['meta'] ?? '', $escape),
            ];
        }

        return $items;
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
     * Validates a Bootstrap variant or returns an empty string.
     */
    private function normalizeVariant(string $variant): string
    {
        $variant = strtolower(trim($variant));

        if ($variant === '') {
            return '';
        }

        return preg_match('/^[a-z0-9_-]+$/', $variant) === 1 ? $variant : '';
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
