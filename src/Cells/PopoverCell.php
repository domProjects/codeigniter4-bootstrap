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
 * Renders a Bootstrap 5 popover trigger through a CodeIgniter 4 Cell.
 */
final class PopoverCell extends Cell
{
    protected string $view = 'popover';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $title = null;

    public mixed $body = null;

    public mixed $placement = null;

    public mixed $trigger = null;

    public mixed $html = false;

    public mixed $variant = null;

    public mixed $type = null;

    public mixed $outline = false;

    public mixed $size = null;

    public mixed $href = null;

    public mixed $tag = null;

    public mixed $buttonType = 'button';

    public mixed $disabled = false;

    public mixed $classes = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the popover trigger payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);
        $tag    = $this->resolveTag();

        return $this->view($this->view, [
            'tag'         => $tag,
            'attributes'  => $this->buildAttributes($tag, $escape),
            'labelHtml'   => $escape ? esc($this->resolveContent()) : $this->resolveContent(),
        ]);
    }

    /**
     * Builds the HTML attributes applied to the trigger element.
     *
     * @return array<string, string>
     */
    private function buildAttributes(string $tag, bool $escape): array
    {
        $attributes = [
            'class'            => $this->buildTriggerClasses(),
            'data-bs-toggle'   => 'popover',
            'data-bs-content'  => $escape ? esc($this->resolveOptionalString($this->body)) : $this->resolveOptionalString($this->body),
        ];

        $title = $this->resolveOptionalString($this->title);

        if ($title !== '') {
            $attributes['title'] = $escape ? esc($title) : $title;
        }

        $placement = strtolower(trim($this->resolveOptionalString($this->placement)));

        if (in_array($placement, ['top', 'bottom', 'start', 'end', 'left', 'right'], true)) {
            $attributes['data-bs-placement'] = $placement;
        }

        $trigger = trim($this->resolveOptionalString($this->trigger));

        if ($trigger !== '') {
            $attributes['data-bs-trigger'] = $trigger;
        }

        if ($this->normalizeBool($this->html)) {
            $attributes['data-bs-html'] = 'true';
        }

        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        if ($tag === 'a') {
            $href = $this->resolveOptionalString($this->href);
            $attributes['href'] = $href === '' ? '#' : $href;
            $attributes['role'] = 'button';

            if ($this->normalizeBool($this->disabled)) {
                $attributes['class'] .= ' disabled';
                $attributes['aria-disabled'] = 'true';
                $attributes['tabindex'] = '-1';
            }

            return $attributes;
        }

        $attributes['type'] = $this->resolveNonEmptyString($this->buttonType, 'button');

        if ($this->normalizeBool($this->disabled)) {
            $attributes['disabled'] = 'disabled';
            $attributes['aria-disabled'] = 'true';
        }

        return $attributes;
    }

    /**
     * Builds the Bootstrap classes applied to the trigger element.
     */
    private function buildTriggerClasses(): string
    {
        $variant = strtolower(trim($this->resolveOptionalString($this->variant ?? $this->type)));

        $classes = ['btn'];

        if ($variant === '' || preg_match('/^[a-z0-9_-]+$/', $variant) !== 1) {
            $variant = 'secondary';
        }

        $classes[] = $this->normalizeBool($this->outline) ? 'btn-outline-' . $variant : 'btn-' . $variant;

        $size = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg'], true)) {
            $classes[] = 'btn-' . $size;
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves whether the trigger should render as a link or a button.
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
     * Chooses the rendered trigger content from `content` or `message`.
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
     * Compacts a CSS class string into a normalized format.
     */
    private function normalizeClasses(string $classes): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized === null ? '' : $normalized;
    }
}
