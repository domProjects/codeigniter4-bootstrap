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
 * Renders a Bootstrap 5 button component through a CodeIgniter 4 Cell.
 */
final class ButtonCell extends Cell
{
    protected string $view = 'button';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $variant = null;

    public mixed $type = null;

    public mixed $outline = false;

    public mixed $size = null;

    public mixed $classes = '';

    public mixed $href = null;

    public mixed $tag = null;

    public mixed $buttonType = 'button';

    public mixed $disabled = false;

    public mixed $escape = true;

    public mixed $role = null;

    /**
     * Prepares the button payload passed to the view.
     */
    public function render(): string
    {
        $content = $this->resolveContent();
        $escape  = $this->normalizeBool($this->escape, true);
        $tag     = $this->resolveTag();
        $href    = $this->resolveOptionalString($this->href);
        $role    = $this->resolveOptionalString($this->role);
        $disabled = $this->normalizeBool($this->disabled);

        if ($tag === 'a' && $role === '') {
            $role = 'button';
        }

        return $this->view($this->view, [
            'attributes'  => $this->buildAttributes($tag, $href, $disabled, $role),
            'buttonHtml'  => $escape ? esc($content) : $content,
            'buttonTag'   => $tag,
        ]);
    }

    /**
     * Builds the HTML attributes for the resolved button tag.
     *
     * @return array<string, string>
     */
    private function buildAttributes(string $tag, string $href, bool $disabled, string $role): array
    {
        $attributes = [
            'class' => $this->buildButtonClasses(),
        ];

        if ($tag === 'a') {
            if (! $disabled && $href !== '') {
                $attributes['href'] = $href;
            }

            if ($role !== '') {
                $attributes['role'] = $role;
            }

            if ($disabled) {
                $attributes['aria-disabled'] = 'true';
                $attributes['tabindex']      = '-1';
            }

            return $attributes;
        }

        $attributes['type'] = $this->resolveNonEmptyString($this->buttonType, 'button');

        if ($disabled) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

    /**
     * Builds the Bootstrap classes applied to the button.
     */
    private function buildButtonClasses(): string
    {
        $variant = $this->normalizeVariant($this->resolveNonEmptyString($this->variant ?? $this->type, 'primary'));
        $prefix  = $this->normalizeBool($this->outline) ? 'btn-outline-' : 'btn-';

        $classes = [
            'btn',
            $prefix . $variant,
        ];

        $size = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg'], true)) {
            $classes[] = 'btn-' . $size;
        }

        if ($this->normalizeBool($this->disabled) && $this->resolveTag() === 'a') {
            $classes[] = 'disabled';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Chooses the rendered button content from `content` or `message`.
     */
    private function resolveContent(): string
    {
        if ($this->content !== null && $this->content !== '') {
            return (string) $this->content;
        }

        return $this->message === null ? '' : (string) $this->message;
    }

    /**
     * Resolves whether the component should render as a link or a button.
     */
    private function resolveTag(): string
    {
        $tag = strtolower(trim($this->resolveOptionalString($this->tag)));

        if ($tag === '') {
            return $this->resolveOptionalString($this->href) !== '' ? 'a' : 'button';
        }

        return in_array($tag, ['a', 'button'], true) ? $tag : 'button';
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
