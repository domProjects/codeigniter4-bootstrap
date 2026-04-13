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
 * Renders a Bootstrap 5 badge component through a CodeIgniter 4 Cell.
 */
final class BadgeCell extends Cell
{
    protected string $view = 'badge';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $variant = null;

    public mixed $type = null;

    public mixed $classes = '';

    public mixed $pill = false;

    public mixed $escape = true;

    public mixed $hiddenText = null;

    /**
     * Prepares the badge payload passed to the view.
     */
    public function render(): string
    {
        $content    = $this->resolveContent();
        $hiddenText = $this->resolveOptionalString($this->hiddenText);
        $escape     = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'badgeClasses'   => $this->buildBadgeClasses(),
            'contentHtml'    => $escape ? esc($content) : $content,
            'hiddenTextHtml' => $hiddenText === '' ? '' : ($escape ? esc($hiddenText) : $hiddenText),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the badge.
     */
    private function buildBadgeClasses(): string
    {
        $classes = [
            'badge',
            'text-bg-' . $this->normalizeVariant($this->resolveNonEmptyString($this->variant ?? $this->type, 'secondary')),
        ];

        if ($this->normalizeBool($this->pill)) {
            $classes[] = 'rounded-pill';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Chooses the rendered badge content from `content` or `message`.
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
            return 'secondary';
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
