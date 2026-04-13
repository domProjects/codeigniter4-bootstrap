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
 * Renders a Bootstrap 5 carousel component through a CodeIgniter 4 Cell.
 */
final class CarouselCell extends Cell
{
    protected string $view = 'carousel';

    public mixed $items = [];

    public mixed $controls = true;

    public mixed $indicators = true;

    public mixed $fade = false;

    public mixed $dark = false;

    public mixed $ride = false;

    public mixed $touch = true;

    public mixed $wrap = true;

    public mixed $interval = null;

    public mixed $classes = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the carousel payload passed to the view.
     */
    public function render(): string
    {
        $carouselId = $this->resolveCarouselId();

        return $this->view($this->view, [
            'carouselId'       => $carouselId,
            'carouselClasses'  => $this->buildCarouselClasses(),
            'items'            => $this->normalizeItems($this->normalizeBool($this->escape, true)),
            'controls'         => $this->normalizeBool($this->controls, true),
            'indicators'       => $this->normalizeBool($this->indicators, true),
            'ride'             => $this->normalizeBool($this->ride),
            'touch'            => $this->normalizeBool($this->touch, true),
            'wrap'             => $this->normalizeBool($this->wrap, true),
            'interval'         => $this->resolveInterval(),
            'dark'             => $this->normalizeBool($this->dark),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the carousel wrapper.
     */
    private function buildCarouselClasses(): string
    {
        $classes = ['carousel', 'slide'];

        if ($this->normalizeBool($this->fade)) {
            $classes[] = 'carousel-fade';
        }

        if ($this->normalizeBool($this->dark)) {
            $classes[] = 'carousel-dark';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes carousel slides into the structure expected by the view.
     *
     * @return list<array<string,string|bool>>
     */
    private function normalizeItems(bool $escape): array
    {
        if (! is_array($this->items)) {
            return [];
        }

        $items = [];

        foreach (array_values($this->items) as $index => $item) {
            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (! is_array($item)) {
                continue;
            }

            $src = $this->resolveOptionalString($item['src'] ?? $item['image'] ?? '');

            if ($src === '') {
                continue;
            }

            $active = array_key_exists('active', $item)
                ? $this->normalizeBool($item['active'])
                : $items === [];

            $items[] = [
                'src'             => $src,
                'alt'             => $this->resolveOptionalString($item['alt'] ?? ''),
                'titleHtml'       => $this->renderValue($this->resolveOptionalString($item['title'] ?? $item['captionTitle'] ?? ''), $escape),
                'captionHtml'     => $this->renderValue($this->resolveOptionalString($item['caption'] ?? $item['captionText'] ?? ''), $escape),
                'interval'        => $this->resolveItemInterval($item['interval'] ?? null),
                'active'          => $active,
                'captionClasses'  => $this->normalizeClasses($this->resolveOptionalString($item['captionClasses'] ?? 'carousel-caption d-none d-md-block')),
            ];
        }

        return $items;
    }

    /**
     * Normalizes a per-slide interval value.
     */
    private function resolveItemInterval(mixed $value): string
    {
        if (is_int($value)) {
            return (string) max(0, $value);
        }

        if (is_string($value) && ctype_digit(trim($value))) {
            return (string) max(0, (int) trim($value));
        }

        return '';
    }

    /**
     * Normalizes the global carousel interval value.
     */
    private function resolveInterval(): string
    {
        if (is_int($this->interval)) {
            return (string) max(0, $this->interval);
        }

        if (is_string($this->interval) && ctype_digit(trim($this->interval))) {
            return (string) max(0, (int) trim($this->interval));
        }

        return '';
    }

    /**
     * Resolves a stable carousel id, generating one when needed.
     */
    private function resolveCarouselId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'carousel-' . substr(md5(serialize([$this->items, $this->classes])), 0, 8);
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
