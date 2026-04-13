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
 * Renders a Bootstrap 5 alert component through a CodeIgniter 4 Cell.
 */
final class AlertCell extends Cell
{
    protected string $view = 'alert';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $heading = null;

    public mixed $variant = null;

    public mixed $type = null;

    public mixed $classes = '';

    public mixed $role = 'alert';

    public mixed $closeLabel = 'Close';

    public mixed $dismissible = false;

    public mixed $escape = true;

    /**
     * Prepares the alert payload passed to the view.
     */
    public function render(): string
    {
        $content = $this->resolveContent();
        $heading = $this->resolveOptionalString($this->heading);
        $escape  = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'alertClasses' => $this->buildAlertClasses(),
            'contentHtml'  => $escape ? esc($content) : $content,
            'headingHtml'  => $heading === '' ? '' : ($escape ? esc($heading) : $heading),
            'closeLabel'   => $this->resolveNonEmptyString($this->closeLabel, 'Close'),
            'dismissible'  => $this->normalizeBool($this->dismissible),
            'role'         => $this->resolveNonEmptyString($this->role, 'alert'),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the alert wrapper.
     */
    private function buildAlertClasses(): string
    {
        $classes = [
            'alert',
            'alert-' . $this->normalizeVariant($this->resolveNonEmptyString($this->variant ?? $this->type, 'primary')),
        ];

        if ($this->normalizeBool($this->dismissible)) {
            $classes[] = 'alert-dismissible';
            $classes[] = 'fade';
            $classes[] = 'show';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Chooses the rendered body content from `content` or `message`.
     */
    private function resolveContent(): string
    {
        if ($this->content !== null && $this->content !== '') {
            return (string) $this->content;
        }

        return $this->message === null ? '' : (string) $this->message;
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
