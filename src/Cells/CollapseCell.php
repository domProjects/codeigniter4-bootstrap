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
 * Renders a Bootstrap 5 collapse component through a CodeIgniter 4 Cell.
 */
final class CollapseCell extends Cell
{
    protected string $view = 'collapse';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $show = false;

    public mixed $horizontal = false;

    public mixed $card = false;

    public mixed $classes = '';

    public mixed $bodyClasses = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the collapse payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'collapseId'      => $this->resolveCollapseId(),
            'collapseClasses' => $this->buildCollapseClasses(),
            'bodyClasses'     => $this->buildBodyClasses(),
            'bodyHtml'        => $escape ? esc($this->resolveContent()) : $this->resolveContent(),
            'wrapBody'        => $this->normalizeBool($this->card) || $this->normalizeClasses($this->resolveOptionalString($this->bodyClasses)) !== '',
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the collapse wrapper.
     */
    private function buildCollapseClasses(): string
    {
        $classes = ['collapse'];

        if ($this->normalizeBool($this->horizontal)) {
            $classes[] = 'collapse-horizontal';
        }

        if ($this->normalizeBool($this->show)) {
            $classes[] = 'show';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the optional collapse body wrapper.
     */
    private function buildBodyClasses(): string
    {
        $classes = [];

        if ($this->normalizeBool($this->card)) {
            $classes[] = 'card';
            $classes[] = 'card-body';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->bodyClasses));

        if ($extra !== '') {
            $classes[] = $extra;
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
     * Resolves a stable collapse id, generating one when needed.
     */
    private function resolveCollapseId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'collapse-' . substr(md5(serialize([$this->content, $this->classes, $this->bodyClasses])), 0, 8);
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
