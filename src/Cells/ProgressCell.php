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
 * Renders a Bootstrap 5 progress component through a CodeIgniter 4 Cell.
 */
final class ProgressCell extends Cell
{
    protected string $view = 'progress';

    public mixed $value = 0;

    public mixed $label = null;

    public mixed $variant = null;

    public mixed $striped = false;

    public mixed $animated = false;

    public mixed $height = null;

    public mixed $classes = '';

    public mixed $bars = [];

    public mixed $min = 0;

    public mixed $max = 100;

    public mixed $escape = true;

    /**
     * Prepares the progress payload passed to the view.
     */
    public function render(): string
    {
        return $this->view($this->view, [
            'progressClasses' => $this->buildProgressClasses(),
            'progressStyle'   => $this->resolveHeightStyle(),
            'bars'            => $this->normalizeBars($this->normalizeBool($this->escape, true)),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the progress wrapper.
     */
    private function buildProgressClasses(): string
    {
        $classes = ['progress'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * @return list<array<string,string|int>>
     */
    /**
     * Normalizes one or more progress bars for rendering.
     *
     * @return list<array<string, mixed>>
     */
    private function normalizeBars(bool $escape): array
    {
        $bars = [];

        if (is_array($this->bars) && $this->bars !== []) {
            foreach ($this->bars as $bar) {
                if (is_object($bar)) {
                    $bar = get_object_vars($bar);
                }

                if (! is_array($bar)) {
                    continue;
                }

                $bars[] = $this->normalizeBar($bar, $escape);
            }

            return array_values(array_filter($bars, static fn (array $bar): bool => $bar !== []));
        }

        return [$this->normalizeBar([
            'value'    => $this->value,
            'label'    => $this->label,
            'variant'  => $this->variant,
            'striped'  => $this->striped,
            'animated' => $this->animated,
        ], $escape)];
    }

    /**
     * @param array<string,mixed> $bar
     *
     * @return array<string,string|int>
     */
    /**
     * Normalizes a single progress bar definition.
     *
     * @return array<string, mixed>
     */
    private function normalizeBar(array $bar, bool $escape): array
    {
        $min   = $this->resolveNumber($bar['min'] ?? $this->min, 0);
        $max   = max($min + 1, $this->resolveNumber($bar['max'] ?? $this->max, 100));
        $value = min($max, max($min, $this->resolveNumber($bar['value'] ?? 0, 0)));

        $classes = ['progress-bar'];
        $variant = strtolower(trim($this->resolveOptionalString($bar['variant'] ?? '')));

        if ($variant !== '' && preg_match('/^[a-z0-9_-]+$/', $variant) === 1) {
            $classes[] = 'text-bg-' . $variant;
        }

        if ($this->normalizeBool($bar['striped'] ?? false)) {
            $classes[] = 'progress-bar-striped';
        }

        if ($this->normalizeBool($bar['animated'] ?? false)) {
            $classes[] = 'progress-bar-animated';
        }

        $label = $this->resolveOptionalString($bar['label'] ?? '');

        return [
            'classes' => $this->normalizeClasses(implode(' ', $classes)),
            'value'   => $value,
            'min'     => $min,
            'max'     => $max,
            'percent' => (string) round((($value - $min) / ($max - $min)) * 100, 2),
            'label'   => $label === '' ? '' : ($escape ? esc($label) : $label),
        ];
    }

    /**
     * Resolves the inline height style for the progress container.
     */
    private function resolveHeightStyle(): string
    {
        $height = trim($this->resolveOptionalString($this->height));

        return $height === '' ? '' : 'height: ' . $height . ';';
    }

    /**
     * Normalizes an integer-like numeric value.
     */
    private function resolveNumber(mixed $value, int $default): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && is_numeric(trim($value))) {
            return (int) trim($value);
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
