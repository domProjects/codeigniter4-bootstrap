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
 * Renders a Bootstrap 5 accordion component through a CodeIgniter 4 Cell.
 */
final class AccordionCell extends Cell
{
    protected string $view = 'accordion';

    public mixed $items = [];

    public mixed $flush = false;

    public mixed $alwaysOpen = false;

    public mixed $classes = '';

    public mixed $id = null;

    public mixed $escape = true;

    /**
     * Prepares the accordion payload passed to the view.
     */
    public function render(): string
    {
        $accordionId = $this->resolveAccordionId();

        return $this->view($this->view, [
            'accordionId'      => $accordionId,
            'accordionClasses' => $this->buildAccordionClasses(),
            'alwaysOpen'       => $this->normalizeBool($this->alwaysOpen),
            'items'            => $this->normalizeItems($accordionId, $this->normalizeBool($this->escape, true)),
        ]);
    }

    /**
     * Builds the Bootstrap classes applied to the accordion container.
     */
    private function buildAccordionClasses(): string
    {
        $classes = ['accordion'];

        if ($this->normalizeBool($this->flush)) {
            $classes[] = 'accordion-flush';
        }

        $extraClasses = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extraClasses !== '') {
            $classes[] = $extraClasses;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Normalizes accordion items into the structure expected by the view.
     *
     * @return list<array<string,string|bool>>
     */
    private function normalizeItems(string $accordionId, bool $escape): array
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

            $title = $this->resolveOptionalString($item['title'] ?? $item['label'] ?? '');

            if ($title === '') {
                continue;
            }

            $content = $this->resolveOptionalString($item['content'] ?? $item['body'] ?? '');
            $active  = $this->normalizeBool($item['active'] ?? false);

            $items[] = [
                'titleHtml'   => $escape ? esc($title) : $title,
                'contentHtml' => $escape ? esc($content) : $content,
                'headingId'   => $accordionId . '-heading-' . $index,
                'collapseId'  => $accordionId . '-collapse-' . $index,
                'active'      => $active,
                'itemClasses' => $this->buildItemClasses($item),
            ];
        }

        return $items;
    }

    /**
     * Builds the classes applied to a single accordion item.
     *
     * @param array<string, mixed> $item
     */
    private function buildItemClasses(array $item): string
    {
        $classes = ['accordion-item'];
        $extra   = $this->normalizeClasses($this->resolveOptionalString($item['classes'] ?? ''));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves a stable accordion id, generating one when needed.
     */
    private function resolveAccordionId(): string
    {
        $id = trim($this->resolveOptionalString($this->id));

        if ($id !== '') {
            return $id;
        }

        return 'accordion-' . substr(md5(serialize([$this->items, $this->classes])), 0, 8);
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
