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
 * Renders a Bootstrap-friendly image element through a CodeIgniter 4 Cell.
 */
final class ImageCell extends Cell
{
    protected string $view = 'image';

    public mixed $src = null;

    public mixed $image = null;

    public mixed $alt = '';

    public mixed $imageAlt = null;

    public mixed $classes = '';

    public mixed $fluid = true;

    public mixed $thumbnail = false;

    public mixed $rounded = false;

    public mixed $align = null;

    public mixed $attrs = [];

    public mixed $sources = [];

    /**
     * Prepares the image payload passed to the view.
     */
    public function render(): string
    {
        $src = $this->resolveSource();

        if ($src === '') {
            return '';
        }

        return $this->view($this->view, [
            'src'            => $src,
            'alt'            => $this->resolveAlt(),
            'imageClasses'   => $this->buildImageClasses(),
            'imageAttrs'     => $this->renderAttributes($this->normalizeImageAttributes()),
            'sourceAttrs'    => $this->normalizeSources(),
        ]);
    }

    /**
     * Resolves the final image source URL.
     */
    private function resolveSource(): string
    {
        $source = $this->resolveOptionalString($this->src);

        if ($source !== '') {
            return $source;
        }

        return $this->resolveOptionalString($this->image);
    }

    /**
     * Resolves the image alternate text.
     */
    private function resolveAlt(): string
    {
        $alt = $this->resolveOptionalString($this->alt);

        if ($alt !== '') {
            return $alt;
        }

        return $this->resolveOptionalString($this->imageAlt);
    }

    /**
     * Builds the Bootstrap classes applied to the image.
     */
    private function buildImageClasses(): string
    {
        $classes = [];

        if ($this->normalizeBool($this->fluid, true)) {
            $classes[] = 'img-fluid';
        }

        if ($this->normalizeBool($this->thumbnail)) {
            $classes[] = 'img-thumbnail';
        }

        $roundedClass = $this->resolveRoundedClass($this->rounded);

        if ($roundedClass !== '') {
            $classes[] = $roundedClass;
        }

        $alignClasses = $this->resolveAlignmentClasses();

        if ($alignClasses !== '') {
            $classes[] = $alignClasses;
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Maps the supported alignment options to Bootstrap utility classes.
     */
    private function resolveAlignmentClasses(): string
    {
        $align = strtolower(trim($this->resolveOptionalString($this->align)));

        return match ($align) {
            'start', 'left' => 'float-start',
            'end', 'right'  => 'float-end',
            'center'        => 'mx-auto d-block',
            default         => '',
        };
    }

    /**
     * Resolves the optional rounded utility class.
     */
    private function resolveRoundedClass(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'rounded' : '';
        }

        if (is_string($value)) {
            $class = $this->normalizeClasses($value);

            if ($class !== '' && preg_match('/^[a-z0-9 _-]+$/i', $class) === 1) {
                return $class;
            }
        }

        return '';
    }

    /**
     * Normalizes `<source>` definitions for a wrapping `<picture>` element.
     *
     * @return list<string>
     */
    private function normalizeSources(): array
    {
        if (! is_array($this->sources)) {
            return [];
        }

        $sources = [];

        foreach ($this->sources as $source) {
            if (is_object($source)) {
                $source = get_object_vars($source);
            }

            if (! is_array($source)) {
                continue;
            }

            $attributes = $this->normalizeAttributes($source);

            if (! array_key_exists('srcset', $attributes)) {
                continue;
            }

            $sources[] = $this->renderAttributes($attributes);
        }

        return $sources;
    }

    /**
     * Normalizes additional image attributes while protecting reserved ones.
     *
     * @return array<string, bool|string>
     */
    private function normalizeImageAttributes(): array
    {
        $attributes = $this->normalizeAttributes($this->attrs);

        unset($attributes['src'], $attributes['alt'], $attributes['class']);

        return $attributes;
    }

    /**
     * Normalizes arbitrary HTML attributes and removes unsupported values.
     *
     * @return array<string, bool|string>
     */
    private function normalizeAttributes(mixed $attributes): array
    {
        if (is_object($attributes)) {
            $attributes = get_object_vars($attributes);
        }

        if (! is_array($attributes)) {
            return [];
        }

        $normalized = [];

        foreach ($attributes as $name => $value) {
            if (! is_string($name)) {
                continue;
            }

            $name = trim($name);

            if ($name === '' || preg_match('/^[A-Za-z_:][A-Za-z0-9:._-]*$/', $name) !== 1) {
                continue;
            }

            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $normalized[$name] = true;

                continue;
            }

            if (is_scalar($value)) {
                $normalized[$name] = (string) $value;
            }
        }

        return $normalized;
    }

    /**
     * Renders normalized attributes into an HTML fragment.
     *
     * @param array<string, bool|string> $attributes
     */
    private function renderAttributes(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $name => $value) {
            if ($value === true) {
                $parts[] = $name;

                continue;
            }

            $parts[] = $name . '="' . esc($value) . '"';
        }

        return $parts === [] ? '' : ' ' . implode(' ', $parts);
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
