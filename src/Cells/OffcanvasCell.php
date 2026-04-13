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
 * Renders a Bootstrap 5 offcanvas component through a CodeIgniter 4 Cell.
 */
final class OffcanvasCell extends Cell
{
    protected string $view = 'offcanvas';

    public mixed $title = null;

    public mixed $message = null;

    public mixed $content = null;

    public mixed $placement = 'start';

    public mixed $responsive = null;

    public mixed $classes = '';

    public mixed $bodyClasses = '';

    public mixed $headerClasses = '';

    public mixed $show = false;

    public mixed $scroll = false;

    public mixed $backdrop = true;

    public mixed $closeButton = true;

    public mixed $theme = null;

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the offcanvas payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'offcanvasId'      => $this->resolveOffcanvasId(),
            'offcanvasClasses' => $this->buildOffcanvasClasses(),
            'headerClasses'    => $this->buildHeaderClasses(),
            'bodyClasses'      => $this->buildBodyClasses(),
            'titleHtml'        => $this->renderValue($this->resolveOptionalString($this->title), $escape),
            'bodyHtml'         => $this->renderValue($this->resolveContent(), $escape),
            'show'             => $this->normalizeBool($this->show),
            'scroll'           => $this->normalizeBool($this->scroll),
            'backdrop'         => $this->resolveBackdrop(),
            'closeButton'      => $this->normalizeBool($this->closeButton),
            'theme'            => strtolower(trim($this->resolveOptionalString($this->theme))),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the offcanvas wrapper.
     */
    private function buildOffcanvasClasses(): string
    {
        $classes = [$this->resolveBaseClass(), 'offcanvas-' . $this->resolvePlacement()];

        if ($this->normalizeBool($this->show)) {
            $classes[] = 'show';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the offcanvas header.
     */
    private function buildHeaderClasses(): string
    {
        $classes = ['offcanvas-header'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->headerClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the offcanvas body.
     */
    private function buildBodyClasses(): string
    {
        $classes = ['offcanvas-body'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->bodyClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves the base offcanvas class, including responsive variants.
     */
    private function resolveBaseClass(): string
    {
        $responsive = strtolower(trim($this->resolveOptionalString($this->responsive)));

        if (in_array($responsive, ['sm', 'md', 'lg', 'xl', 'xxl'], true)) {
            return 'offcanvas-' . $responsive;
        }

        return 'offcanvas';
    }

    /**
     * Validates the requested offcanvas placement.
     */
    private function resolvePlacement(): string
    {
        $placement = strtolower(trim($this->resolveOptionalString($this->placement)));

        return in_array($placement, ['start', 'end', 'top', 'bottom'], true) ? $placement : 'start';
    }

    /**
     * Normalizes the backdrop option into the Bootstrap data attribute value.
     */
    private function resolveBackdrop(): string
    {
        $value = $this->backdrop;

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            if (in_array($normalized, ['static', 'true', 'false'], true)) {
                return $normalized;
            }
        }

        return $this->normalizeBool($value, true) ? 'true' : 'false';
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
     * Resolves a stable offcanvas id, generating one when needed.
     */
    private function resolveOffcanvasId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'offcanvas-' . substr(md5(serialize([$this->title, $this->content, $this->classes])), 0, 8);
    }

    /**
     * Renders an optional value as escaped or raw HTML.
     */
    private function renderValue(string $value, bool $escape): string
    {
        if ($value === '') {
            return '';
        }

        return $escape ? esc($value) : $value;
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
