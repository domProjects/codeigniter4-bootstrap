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
 * Renders a Bootstrap 5 modal component through a CodeIgniter 4 Cell.
 */
final class ModalCell extends Cell
{
    protected string $view = 'modal';

    public mixed $title = null;

    public mixed $message = null;

    public mixed $content = null;

    public mixed $footer = null;

    public mixed $classes = '';

    public mixed $dialogClasses = '';

    public mixed $size = null;

    public mixed $centered = false;

    public mixed $scrollable = false;

    public mixed $fade = true;

    public mixed $show = false;

    public mixed $closeButton = true;

    public mixed $staticBackdrop = false;

    public mixed $keyboard = true;

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the modal payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'modalId'       => $this->resolveModalId(),
            'modalClasses'  => $this->buildModalClasses(),
            'dialogClasses' => $this->buildDialogClasses(),
            'titleHtml'     => $this->renderValue($this->resolveOptionalString($this->title), $escape),
            'bodyHtml'      => $this->renderValue($this->resolveContent(), $escape),
            'footerHtml'    => $this->renderValue($this->resolveOptionalString($this->footer), $escape),
            'closeButton'   => $this->normalizeBool($this->closeButton),
            'staticBackdrop'=> $this->normalizeBool($this->staticBackdrop),
            'keyboard'      => $this->normalizeBool($this->keyboard, true),
            'show'          => $this->normalizeBool($this->show),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the modal wrapper.
     */
    private function buildModalClasses(): string
    {
        $classes = ['modal'];

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
     * Builds the classes applied to the modal dialog element.
     */
    private function buildDialogClasses(): string
    {
        $classes = ['modal-dialog'];

        $size = strtolower(trim($this->resolveOptionalString($this->size)));

        if (in_array($size, ['sm', 'lg', 'xl', 'fullscreen'], true)) {
            $classes[] = 'modal-' . $size;
        }

        if ($this->normalizeBool($this->centered)) {
            $classes[] = 'modal-dialog-centered';
        }

        if ($this->normalizeBool($this->scrollable)) {
            $classes[] = 'modal-dialog-scrollable';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->dialogClasses));

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
     * Resolves a stable modal id, generating one when needed.
     */
    private function resolveModalId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'modal-' . substr(md5(serialize([$this->title, $this->content, $this->classes])), 0, 8);
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
