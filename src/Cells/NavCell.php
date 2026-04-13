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
 * Renders Bootstrap 5 navs, tabs, or pills through a CodeIgniter 4 Cell.
 */
final class NavCell extends Cell
{
    protected string $view = 'nav';

    public mixed $items = [];

    public mixed $variant = 'tabs';

    public mixed $fill = false;

    public mixed $justified = false;

    public mixed $vertical = false;

    public mixed $fade = false;

    public mixed $classes = '';

    public mixed $contentClasses = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the nav payload passed to the view.
     */
    public function render(): string
    {
        $baseId    = $this->resolveBaseId();
        $hasPanes  = $this->hasPanes();
        $escape    = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'navClasses'     => $this->buildNavClasses(),
            'contentClasses' => $this->buildContentClasses(),
            'hasPanes'       => $hasPanes,
            'items'          => $this->normalizeItems($baseId, $hasPanes, $escape),
            'fade'           => $this->normalizeBool($this->fade),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the nav container.
     */
    private function buildNavClasses(): string
    {
        $classes = ['nav'];
        $variant = strtolower(trim($this->resolveOptionalString($this->variant)));

        if (in_array($variant, ['tabs', 'pills', 'underline'], true)) {
            $classes[] = 'nav-' . $variant;
        }

        if ($this->normalizeBool($this->fill)) {
            $classes[] = 'nav-fill';
        }

        if ($this->normalizeBool($this->justified)) {
            $classes[] = 'nav-justified';
        }

        if ($this->normalizeBool($this->vertical)) {
            $classes[] = 'flex-column';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the optional pane container.
     */
    private function buildContentClasses(): string
    {
        $classes = ['tab-content'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($this->contentClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes nav items and their optional tab panes.
     *
     * @return list<array<string, mixed>>
     */
    private function normalizeItems(string $baseId, bool $hasPanes, bool $escape): array
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

            $label = $this->resolveOptionalString($item['label'] ?? $item['title'] ?? '');

            if ($label === '') {
                continue;
            }

            $active   = array_key_exists('active', $item) ? $this->normalizeBool($item['active']) : $items === [];
            $disabled = $this->normalizeBool($item['disabled'] ?? false);
            $url      = $this->resolveOptionalString($item['url'] ?? $item['href'] ?? '#');
            $paneId   = $baseId . '-pane-' . $index;
            $itemId   = $baseId . '-item-' . $index;
            $content  = $this->resolveOptionalString($item['content'] ?? '');
            $toggle   = strtolower(trim($this->resolveOptionalString($item['toggle'] ?? '')));

            if ($toggle === '' && $hasPanes) {
                $variant = strtolower(trim($this->resolveOptionalString($this->variant)));
                $toggle  = $variant === 'pills' ? 'pill' : 'tab';
            }

            if ($hasPanes) {
                $url = '#' . $paneId;
            }

            $linkClasses = ['nav-link'];

            if ($active) {
                $linkClasses[] = 'active';
            }

            if ($disabled) {
                $linkClasses[] = 'disabled';
            }

            $extraLinkClasses = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

            if ($extraLinkClasses !== '') {
                $linkClasses[] = $extraLinkClasses;
            }

            $attributes = [
                'class' => $this->normalizeClasses(implode(' ', $linkClasses)),
                'href'  => $url,
            ];

            if ($hasPanes && in_array($toggle, ['tab', 'pill'], true)) {
                $attributes['data-bs-toggle'] = $toggle;
                $attributes['role']           = 'tab';
                $attributes['aria-controls']  = $paneId;
                $attributes['aria-selected']  = $active ? 'true' : 'false';
                $attributes['id']             = $itemId;
            } elseif ($active) {
                $attributes['aria-current'] = 'page';
            }

            if ($disabled) {
                unset($attributes['href']);
                $attributes['aria-disabled'] = 'true';
                $attributes['tabindex']      = '-1';
            }

            $items[] = [
                'labelHtml'    => $escape ? esc($label) : $label,
                'contentHtml'  => $content === '' ? '' : ($escape ? esc($content) : $content),
                'attributes'   => $attributes,
                'paneId'       => $paneId,
                'itemId'       => $itemId,
                'active'       => $active,
                'disabled'     => $disabled,
            ];
        }

        return $items;
    }

    /**
     * Detects whether any item should render an associated pane.
     */
    private function hasPanes(): bool
    {
        if (! is_array($this->items)) {
            return false;
        }

        foreach ($this->items as $item) {
            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (! is_array($item)) {
                continue;
            }

            if ($this->resolveOptionalString($item['content'] ?? '') !== '' || $this->resolveOptionalString($item['toggle'] ?? '') !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolves a stable base id, generating one when needed.
     */
    private function resolveBaseId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'nav-' . substr(md5(serialize([$this->items, $this->variant, $this->classes])), 0, 8);
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
