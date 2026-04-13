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
 * Renders a Bootstrap 5 spinner component through a CodeIgniter 4 Cell.
 */
final class SpinnerCell extends Cell
{
    protected string $view = 'spinner';

    public mixed $variant = null;

    public mixed $type = 'border';

    public mixed $small = false;

    public mixed $label = 'Loading...';

    public mixed $classes = '';

    public mixed $role = 'status';

    public mixed $escape = true;

    /**
     * Prepares the spinner payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'spinnerClasses' => $this->buildSpinnerClasses(),
            'labelHtml'      => $escape ? esc($this->resolveOptionalString($this->label)) : $this->resolveOptionalString($this->label),
            'role'           => $this->resolveNonEmptyString($this->role, 'status'),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the spinner element.
     */
    private function buildSpinnerClasses(): string
    {
        $classes = [
            $this->resolveType() === 'grow' ? 'spinner-grow' : 'spinner-border',
        ];

        $variant = strtolower(trim($this->resolveOptionalString($this->variant)));

        if ($variant !== '' && preg_match('/^[a-z0-9_-]+$/', $variant) === 1) {
            $classes[] = 'text-' . $variant;
        }

        if ($this->normalizeBool($this->small)) {
            $classes[] = $this->resolveType() === 'grow' ? 'spinner-grow-sm' : 'spinner-border-sm';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves the spinner type between border and grow.
     */
    private function resolveType(): string
    {
        return strtolower(trim($this->resolveOptionalString($this->type))) === 'grow' ? 'grow' : 'border';
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
