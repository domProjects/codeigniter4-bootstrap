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
 * Renders a Bootstrap empty state through a CodeIgniter 4 Cell.
 */
final class EmptyStateCell extends Cell
{
    protected string $view = 'empty_state';

    public mixed $title = 'Nothing here yet';

    public mixed $content = null;

    public mixed $message = null;

    public mixed $actions = [];

    public mixed $classes = 'text-center py-5';

    public mixed $bodyClasses = 'mx-auto';

    public mixed $maxWidth = '36rem';

    public mixed $escape = true;

    /**
     * Prepares the empty-state payload passed to the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'wrapperClasses' => $this->normalizeClasses($this->resolveOptionalString($this->classes)),
            'bodyClasses'    => $this->normalizeClasses($this->resolveOptionalString($this->bodyClasses)),
            'maxWidth'       => $this->resolveOptionalString($this->maxWidth),
            'titleHtml'      => $this->renderOptionalHtml($this->title, $escape),
            'contentHtml'    => $this->renderOptionalHtml($this->content ?? $this->message, $escape),
            'actions'        => $this->normalizeActions($escape),
        ]);
    }

    /**
     * Normalizes the actions rendered below the empty state.
     *
     * @return list<array{
     *     tag: 'a'|'button',
     *     html: string,
     *     attributes: array<string, string>
     * }>
     */
    private function normalizeActions(bool $escape): array
    {
        if (! is_array($this->actions)) {
            return [];
        }

        $actions = [];

        foreach ($this->actions as $action) {
            if (is_object($action)) {
                $action = get_object_vars($action);
            }

            if (! is_array($action)) {
                continue;
            }

            $label = $this->resolveOptionalString($action['label'] ?? $action['content'] ?? '');

            if ($label === '') {
                continue;
            }

            $variant = $this->normalizeVariant($this->resolveOptionalString($action['variant'] ?? 'primary'));
            $tag     = $this->resolveOptionalString($action['href'] ?? $action['url'] ?? '') !== '' ? 'a' : 'button';
            $classes = ['btn', 'btn-' . $variant];
            $extra   = $this->normalizeClasses($this->resolveOptionalString($action['classes'] ?? ''));

            if ($extra !== '') {
                $classes[] = $extra;
            }

            $attributes = [
                'class' => $this->normalizeClasses(implode(' ', $classes)),
            ];

            if ($tag === 'a') {
                $attributes['href'] = $this->resolveOptionalString($action['href'] ?? $action['url'] ?? '');
            } else {
                $attributes['type'] = $this->resolveOptionalString($action['buttonType'] ?? 'button');
            }

            $actions[] = [
                'tag'        => $tag,
                'html'       => $escape ? esc($label) : $label,
                'attributes' => $attributes,
            ];
        }

        return $actions;
    }

    /**
     * Renders an optional value as escaped or raw HTML.
     */
    private function renderOptionalHtml(mixed $value, bool $escape): string
    {
        $content = $this->resolveOptionalString($value);

        if ($content === '') {
            return '';
        }

        return $escape ? esc($content) : $content;
    }

    /**
     * Validates a Bootstrap variant and applies a safe fallback.
     */
    private function normalizeVariant(string $variant): string
    {
        $variant = strtolower(trim($variant));

        if ($variant === '' || preg_match('/^[a-z0-9_-]+$/', $variant) !== 1) {
            return 'primary';
        }

        return $variant;
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
