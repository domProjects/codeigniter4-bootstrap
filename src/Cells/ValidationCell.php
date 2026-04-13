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
 * Renders Bootstrap 5 validation feedback through a CodeIgniter 4 Cell.
 */
final class ValidationCell extends Cell
{
    protected string $view = 'validation';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $state = 'invalid';

    public mixed $type = null;

    public mixed $tooltip = false;

    public mixed $classes = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the validation message payload passed to the view.
     */
    public function render(): string
    {
        $content = $this->resolveContent();
        $escape  = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'attributes' => $this->buildAttributes(),
            'html'       => $escape ? esc($content) : $content,
        ]);
    }

    /**
     * Builds the HTML attributes applied to the feedback block.
     *
     * @return array<string, string>
     */
    private function buildAttributes(): array
    {
        $attributes = [
            'class' => $this->buildClasses(),
        ];

        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            $attributes['id'] = $id;
        }

        return $attributes;
    }

    /**
     * Resolves the Bootstrap classes from the state and tooltip mode.
     */
    private function buildClasses(): string
    {
        $state   = $this->normalizeState($this->resolveOptionalString($this->state ?? $this->type));
        $classes = [$state . '-' . ($this->normalizeBool($this->tooltip) ? 'tooltip' : 'feedback')];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Chooses the rendered content from `content` or `message`.
     */
    private function resolveContent(): string
    {
        if ($this->content !== null && $this->content !== '') {
            return (string) $this->content;
        }

        return $this->message === null ? '' : (string) $this->message;
    }

    /**
     * Restricts the validation state to `valid` or `invalid`.
     */
    private function normalizeState(string $state): string
    {
        $state = strtolower(trim($state));

        return $state === 'valid' ? 'valid' : 'invalid';
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
