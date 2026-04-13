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
 * Renders a Bootstrap 5 toast component through a CodeIgniter 4 Cell.
 */
final class ToastCell extends Cell
{
    protected string $view = 'toast';

    public mixed $title = null;

    public mixed $subtitle = null;

    public mixed $message = null;

    public mixed $content = null;

    public mixed $classes = '';

    public mixed $bodyClasses = '';

    public mixed $headerClasses = '';

    public mixed $autoHide = true;

    public mixed $delay = 5000;

    public mixed $fade = true;

    public mixed $show = false;

    public mixed $closeButton = true;

    public mixed $role = 'alert';

    public mixed $ariaLive = 'assertive';

    public mixed $ariaAtomic = 'true';

    public mixed $escape = true;

    /**
     * Prepares the toast payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'toastClasses'  => $this->buildToastClasses(),
            'bodyClasses'   => $this->buildBodyClasses(),
            'headerClasses' => $this->buildHeaderClasses(),
            'titleHtml'     => $this->renderValue($this->resolveOptionalString($this->title), $escape),
            'subtitleHtml'  => $this->renderValue($this->resolveOptionalString($this->subtitle), $escape),
            'bodyHtml'      => $this->renderValue($this->resolveContent(), $escape),
            'autoHide'      => $this->normalizeBool($this->autoHide, true),
            'delay'         => $this->resolveDelay(),
            'closeButton'   => $this->normalizeBool($this->closeButton),
            'role'          => $this->resolveNonEmptyString($this->role, 'alert'),
            'ariaLive'      => $this->resolveNonEmptyString($this->ariaLive, 'assertive'),
            'ariaAtomic'    => $this->resolveNonEmptyString($this->ariaAtomic, 'true'),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the toast wrapper.
     */
    private function buildToastClasses(): string
    {
        $classes = ['toast'];

        if ($this->normalizeBool($this->fade, true)) {
            $classes[] = 'fade';
        }

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
     * Builds the classes applied to the toast body.
     */
    private function buildBodyClasses(): string
    {
        $classes = ['toast-body'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->bodyClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the toast header.
     */
    private function buildHeaderClasses(): string
    {
        $classes = ['toast-header'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->headerClasses));

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
     * Normalizes the toast auto-hide delay.
     */
    private function resolveDelay(): string
    {
        $delay = $this->delay;

        if (is_int($delay)) {
            return (string) max(0, $delay);
        }

        if (is_string($delay) && ctype_digit(trim($delay))) {
            return (string) max(0, (int) trim($delay));
        }

        return '5000';
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
