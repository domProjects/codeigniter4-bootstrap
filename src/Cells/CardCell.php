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
 * Renders a Bootstrap 5 card component through a CodeIgniter 4 Cell.
 */
final class CardCell extends Cell
{
    protected string $view = 'card';

    public mixed $message = null;

    public mixed $content = null;

    public mixed $title = null;

    public mixed $subtitle = null;

    public mixed $header = null;

    public mixed $footer = null;

    public mixed $image = null;

    public mixed $imageAlt = '';

    public mixed $imagePosition = 'top';

    public mixed $classes = '';

    public mixed $bodyClasses = '';

    public mixed $titleTag = 'h5';

    public mixed $escape = true;

    /**
     * Prepares the card payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'cardClasses'      => $this->buildCardClasses(),
            'bodyClasses'      => $this->buildBodyClasses(),
            'contentHtml'      => $this->renderValue($this->resolveContent(), $escape),
            'titleHtml'        => $this->renderValue($this->resolveOptionalString($this->title), $escape),
            'subtitleHtml'     => $this->renderValue($this->resolveOptionalString($this->subtitle), $escape),
            'headerHtml'       => $this->renderValue($this->resolveOptionalString($this->header), $escape),
            'footerHtml'       => $this->renderValue($this->resolveOptionalString($this->footer), $escape),
            'imageUrl'         => $this->resolveOptionalString($this->image),
            'imageAlt'         => $this->resolveOptionalString($this->imageAlt),
            'imagePosition'    => $this->resolveImagePosition(),
            'titleTag'         => $this->resolveTitleTag(),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the card wrapper.
     */
    private function buildCardClasses(): string
    {
        $classes = ['card'];

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the card body.
     */
    private function buildBodyClasses(): string
    {
        $classes = ['card-body'];

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->bodyClasses));

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
     * Validates the card image position.
     */
    private function resolveImagePosition(): string
    {
        $position = strtolower(trim($this->resolveOptionalString($this->imagePosition)));

        return in_array($position, ['top', 'bottom'], true) ? $position : 'top';
    }

    /**
     * Validates the heading tag used for the card title.
     */
    private function resolveTitleTag(): string
    {
        $tag = strtolower(trim($this->resolveOptionalString($this->titleTag)));

        return preg_match('/^h[1-6]$/', $tag) === 1 ? $tag : 'h5';
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
