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
 * Renders Bootstrap 5 tabs or pills through a CodeIgniter 4 Cell.
 */
final class TabsCell extends Cell
{
    protected string $view = 'tabs';

    public mixed $items = [];

    public mixed $pills = false;

    public mixed $fill = false;

    public mixed $justified = false;

    public mixed $fade = false;

    public mixed $classes = '';

    public mixed $navClasses = '';

    public mixed $contentClasses = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the tabs payload passed to the view.
     */
    public function render(): string
    {
        $baseId = $this->resolveBaseId();

        return $this->view($this->view, [
            'navClasses'     => $this->buildNavClasses(),
            'contentClasses' => $this->buildContentClasses(),
            'items'          => $this->normalizeItems($baseId, $this->normalizeBool($this->escape, true)),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the nav tabs list.
     */
    private function buildNavClasses(): string
    {
        $classes = [
            'nav',
            $this->normalizeBool($this->pills) ? 'nav-pills' : 'nav-tabs',
        ];

        if ($this->normalizeBool($this->fill)) {
            $classes[] = 'nav-fill';
        }

        if ($this->normalizeBool($this->justified)) {
            $classes[] = 'nav-justified';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        $extraNavClasses = $this->normalizeClasses($this->resolveOptionalString($this->navClasses));

        if ($extraNavClasses !== '') {
            $classes[] = $extraNavClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the tab content wrapper.
     */
    private function buildContentClasses(): string
    {
        $classes = ['tab-content'];

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->contentClasses));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes tabs and panes into the structure expected by the view.
     *
     * @return list<array<string,string|bool>>
     */
    private function normalizeItems(string $baseId, bool $escape): array
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

            $content = $this->resolveOptionalString($item['content'] ?? '');
            $active  = array_key_exists('active', $item)
                ? $this->normalizeBool($item['active'])
                : $items === [];
            $disabled = $this->normalizeBool($item['disabled'] ?? false);
            $paneId   = $baseId . '-pane-' . $index;
            $tabId    = $baseId . '-tab-' . $index;

            $items[] = [
                'labelHtml'   => $escape ? esc($label) : $label,
                'contentHtml' => $escape ? esc($content) : $content,
                'active'      => $active,
                'disabled'    => $disabled,
                'tabId'       => $tabId,
                'paneId'      => $paneId,
            ];
        }

        return $items;
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

        return 'tabs-' . substr(md5(serialize([$this->items, $this->classes, $this->navClasses])), 0, 8);
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
