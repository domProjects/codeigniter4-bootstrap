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
 * Renders a Bootstrap 5 navbar component through a CodeIgniter 4 Cell.
 */
final class NavbarCell extends Cell
{
    protected string $view = 'navbar';

    public mixed $brandLabel = null;

    public mixed $brandUrl = '/';

    public mixed $items = [];

    public mixed $expand = 'lg';

    public mixed $background = 'body-tertiary';

    public mixed $theme = null;

    public mixed $container = 'fluid';

    public mixed $classes = '';

    public mixed $navClasses = 'me-auto mb-2 mb-lg-0';

    public mixed $content = null;

    public mixed $collapseId = null;

    public mixed $escape = true;

    /**
     * Prepares the navbar payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'navbarClasses'  => $this->buildNavbarClasses(),
            'containerClass' => $this->resolveContainerClass(),
            'brandLabelHtml' => $this->renderValue($this->resolveOptionalString($this->brandLabel), $escape),
            'brandUrl'       => $this->resolveOptionalString($this->brandUrl),
            'items'          => $this->normalizeItems($escape),
            'navClasses'     => $this->normalizeClasses($this->resolveOptionalString($this->navClasses)),
            'contentHtml'    => $this->renderValue($this->resolveOptionalString($this->content), $escape),
            'collapseId'     => $this->resolveCollapseId(),
            'theme'          => strtolower(trim($this->resolveOptionalString($this->theme))),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the navbar wrapper.
     */
    private function buildNavbarClasses(): string
    {
        $classes = ['navbar'];

        $expand = strtolower(trim($this->resolveOptionalString($this->expand)));

        if ($expand !== '') {
            $classes[] = 'navbar-expand-' . $expand;
        }

        $background = trim($this->resolveOptionalString($this->background));

        if ($background !== '') {
            $classes[] = str_starts_with($background, 'bg-') ? $background : 'bg-' . $background;
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves the container class used inside the navbar.
     */
    private function resolveContainerClass(): string
    {
        $container = strtolower(trim($this->resolveOptionalString($this->container)));

        return match ($container) {
            '', 'none', 'false' => '',
            'default', 'container' => 'container',
            default => $container === 'fluid' ? 'container-fluid' : $container,
        };
    }

    /**
     * Normalizes navbar items into the structure expected by the view.
     *
     * @return list<array{labelHtml:string,url:string,active:bool,disabled:bool,classes:string}>
     */
    private function normalizeItems(bool $escape): array
    {
        if (! is_array($this->items)) {
            return [];
        }

        $items = [];

        foreach ($this->items as $item) {
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

            $items[] = [
                'labelHtml' => $escape ? esc($label) : $label,
                'url'       => $this->resolveOptionalString($item['url'] ?? $item['href'] ?? ''),
                'active'    => $this->normalizeBool($item['active'] ?? false),
                'disabled'  => $this->normalizeBool($item['disabled'] ?? false),
                'classes'   => $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? '')),
            ];
        }

        return $items;
    }

    /**
     * Resolves a stable collapse id, generating one when needed.
     */
    private function resolveCollapseId(): string
    {
        $collapseId = trim($this->resolveOptionalString($this->collapseId));

        if ($collapseId !== '') {
            return $collapseId;
        }

        return 'navbar-' . substr(md5(serialize([
            $this->brandLabel,
            $this->brandUrl,
            $this->items,
            $this->content,
        ])), 0, 8);
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
