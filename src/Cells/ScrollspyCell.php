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
 * Renders a Bootstrap 5 scrollspy example through a CodeIgniter 4 Cell.
 */
final class ScrollspyCell extends Cell
{
    protected string $view = 'scrollspy';

    public mixed $items = [];

    public mixed $id = null;

    public mixed $navType = 'nav';

    public mixed $navVariant = 'pills';

    public mixed $height = '260px';

    public mixed $rootMargin = '0px 0px -40%';

    public mixed $classes = '';

    public mixed $navClasses = '';

    public mixed $contentClasses = '';

    public mixed $escape = true;

    /**
     * Prepares the scrollspy payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'wrapperClasses'   => $this->normalizeClasses($this->resolveOptionalString($this->classes)),
            'navType'          => $this->resolveNavType(),
            'navAttributes'    => $this->buildNavAttributes(),
            'contentAttributes'=> $this->buildContentAttributes(),
            'items'            => $this->normalizeItems($escape),
        ]);
    }

    /**
     * Resolves a stable nav id, generating one when needed.
     */
    private function resolveNavId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'scrollspy-' . substr(md5(serialize([$this->items, $this->navType, $this->classes])), 0, 8);
    }

    /**
     * Resolves the Bootstrap nav type used by the scrollspy navigation.
     */
    private function resolveNavType(): string
    {
        $type = strtolower(trim($this->resolveOptionalString($this->navType)));

        return $type === 'list-group' ? 'list-group' : 'nav';
    }

    /**
     * @return array<string, string>
     */
    /**
     * Builds the HTML attributes for the navigation element.
     *
     * @return array<string, string>
     */
    private function buildNavAttributes(): array
    {
        $classes = [];

        if ($this->resolveNavType() === 'list-group') {
            $classes[] = 'list-group';
        } else {
            $classes[] = 'nav';
            $classes[] = strtolower(trim($this->resolveOptionalString($this->navVariant))) === 'tabs' ? 'nav-tabs' : 'nav-pills';
            $classes[] = 'flex-column';
        }

        $classes[] = 'mb-3';

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->navClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return [
            'id'    => $this->resolveNavId(),
            'class' => $this->normalizeClasses(implode(' ', $classes)),
        ];
    }

    /**
     * @return array<string, string>
     */
    /**
     * Builds the HTML attributes for the scrollable content area.
     *
     * @return array<string, string>
     */
    private function buildContentAttributes(): array
    {
        $classes = [
            'scrollspy-example',
            'bg-body-tertiary',
            'p-3',
            'rounded-2',
        ];

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->contentClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return [
            'data-bs-spy'         => 'scroll',
            'data-bs-target'      => '#' . $this->resolveNavId(),
            'data-bs-root-margin' => $this->resolveNonEmptyString($this->rootMargin, '0px 0px -40%'),
            'class'               => $this->normalizeClasses(implode(' ', $classes)),
            'tabindex'            => '0',
            'style'               => 'height: ' . $this->resolveNonEmptyString($this->height, '260px') . '; overflow-y: auto;',
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    /**
     * Normalizes scrollspy sections and links for rendering.
     *
     * @return list<array<string, string>>
     */
    private function normalizeItems(bool $escape): array
    {
        if (! is_array($this->items)) {
            return [];
        }

        $items = [];

        foreach ($this->items as $index => $item) {
            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (! is_array($item)) {
                continue;
            }

            $label = $this->resolveOptionalString($item['label'] ?? $item['title'] ?? '');

            if ($label === '') {
                continue;
            }

            $sectionId = trim($this->resolveOptionalString($item['id'] ?? ''));

            if ($sectionId === '') {
                $sectionId = $this->resolveNavId() . '-section-' . ($index + 1);
            }

            $title        = $this->resolveOptionalString($item['title'] ?? $label);
            $body         = $this->resolveOptionalString($item['content'] ?? $item['body'] ?? '');
            $headingLevel = strtolower(trim($this->resolveOptionalString($item['headingLevel'] ?? 'h4')));

            if (! in_array($headingLevel, ['h2', 'h3', 'h4', 'h5', 'h6'], true)) {
                $headingLevel = 'h4';
            }

            $items[] = [
                'id'           => $sectionId,
                'linkHtml'     => $escape ? esc($label) : $label,
                'linkClasses'  => $this->resolveNavType() === 'list-group' ? 'list-group-item list-group-item-action' : 'nav-link',
                'headingHtml'  => $escape ? esc($title) : $title,
                'headingLevel' => $headingLevel,
                'contentHtml'  => $body === '' ? '' : ($escape ? esc($body) : $body),
                'bodyClasses'  => $this->normalizeClasses('mb-4 ' . $this->resolveOptionalString($item['classes'] ?? '')),
            ];
        }

        return $items;
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
