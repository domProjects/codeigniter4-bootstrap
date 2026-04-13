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
 * Renders a Bootstrap 5 close button component through a CodeIgniter 4 Cell.
 */
final class CloseButtonCell extends Cell
{
    protected string $view = 'close_button';

    public mixed $label = 'Close';

    public mixed $classes = '';

    public mixed $dismiss = null;

    public mixed $target = null;

    public mixed $type = 'button';

    public mixed $disabled = false;

    public mixed $theme = null;

    public mixed $id = null;

    /**
     * Prepares the close button payload passed to the view.
     */
    public function render(): string
    {
        return $this->view($this->view, [
            'attributes' => $this->buildAttributes(),
        ]);
    }

    /**
     * Builds the HTML attributes applied to the close button.
     *
     * @return array<string, string>
     */
    private function buildAttributes(): array
    {
        $attributes = [
            'type'       => $this->resolveNonEmptyString($this->type, 'button'),
            'class'      => $this->buildClasses(),
            'aria-label' => $this->resolveNonEmptyString($this->label, 'Close'),
        ];

        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        $dismiss = strtolower(trim($this->resolveOptionalString($this->dismiss)));

        if (in_array($dismiss, ['alert', 'modal', 'offcanvas', 'toast'], true)) {
            $attributes['data-bs-dismiss'] = $dismiss;
        }

        $target = trim($this->resolveOptionalString($this->target));

        if ($target !== '') {
            $attributes['data-bs-target'] = $target;
        }

        $theme = strtolower(trim($this->resolveOptionalString($this->theme)));

        if (in_array($theme, ['light', 'dark'], true)) {
            $attributes['data-bs-theme'] = $theme;
        }

        if ($this->normalizeBool($this->disabled)) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

    /**
     * Builds the Bootstrap classes applied to the close button.
     */
    private function buildClasses(): string
    {
        $classes = ['btn-close'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
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
