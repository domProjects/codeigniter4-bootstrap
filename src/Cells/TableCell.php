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
 * Renders a Bootstrap 5 table component through a CodeIgniter 4 Cell.
 */
final class TableCell extends Cell
{
    protected string $view = 'table';

    public mixed $headers = [];

    public mixed $rows = [];

    public mixed $footer = [];

    public mixed $caption = null;

    public mixed $captionTop = false;

    public mixed $variant = null;

    public mixed $striped = false;

    public mixed $stripedColumns = false;

    public mixed $hover = false;

    public mixed $bordered = false;

    public mixed $borderless = false;

    public mixed $small = false;

    public mixed $responsive = false;

    public mixed $responsiveBreakpoint = null;

    public mixed $stacked = false;

    public mixed $stackedBreakpoint = null;

    public mixed $emptyMessage = 'No data available.';

    public mixed $emptyClasses = 'text-center text-body-secondary';

    public mixed $actionsHeader = 'Actions';

    public mixed $actionsCellClasses = 'text-nowrap';

    public mixed $classes = '';

    public mixed $wrapperClasses = '';

    public mixed $headVariant = null;

    public mixed $escape = true;

    /**
     * Prepares the full table payload and render variants for the view.
     */
    public function render(): string
    {
        $escape = $this->normalizeBool($this->escape, true);

        return $this->view($this->view, [
            'tableClasses'   => $this->buildTableClasses(),
            'wrapperClasses' => $this->buildWrapperClasses(),
            'captionHtml'    => $this->renderOptionalHtml($this->caption, $escape),
            'captionTop'     => $this->normalizeBool($this->captionTop),
            'headers'        => $this->normalizeHeaders($escape),
            'rows'           => $this->normalizeRows($escape),
            'footer'         => $this->normalizeFooter($escape),
            'headClasses'    => $this->buildHeadClasses(),
            'emptyRow'       => $this->normalizeEmptyRow($escape),
        ]);
    }

    /**
     * Builds the CSS classes applied to the Bootstrap table.
     */
    private function buildTableClasses(): string
    {
        $classes = ['table'];
        $variant = $this->normalizeVariant($this->resolveOptionalString($this->variant));

        if ($variant !== '') {
            $classes[] = 'table-' . $variant;
        }

        foreach ([
            'striped'        => 'table-striped',
            'stripedColumns' => 'table-striped-columns',
            'hover'          => 'table-hover',
            'bordered'       => 'table-bordered',
            'borderless'     => 'table-borderless',
            'small'          => 'table-sm',
        ] as $property => $className) {
            if ($this->normalizeBool($this->{$property})) {
                $classes[] = $className;
            }
        }

        if ($this->normalizeBool($this->stacked)) {
            $classes[] = 'table-stack';
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->classes));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Builds the classes applied to the outer wrapper, including responsive variants.
     */
    private function buildWrapperClasses(): string
    {
        $classes = [];

        if ($this->normalizeBool($this->responsive)) {
            $breakpoint = strtolower(trim($this->resolveOptionalString($this->responsiveBreakpoint)));
            $classes[]  = $breakpoint === '' ? 'table-responsive' : 'table-responsive-' . $breakpoint;
        }

        if ($this->normalizeBool($this->stacked)) {
            $breakpoint = strtolower(trim($this->resolveOptionalString($this->stackedBreakpoint)));
            $classes[]  = $breakpoint === '' ? 'table-stack-responsive' : 'table-stack-responsive-' . $breakpoint;
        }

        $extra = $this->normalizeClasses($this->resolveOptionalString($this->wrapperClasses));

        if ($extra !== '') {
            $classes[] = $extra;
        }

        return $this->normalizeClasses(implode(' ', $classes));
    }

    /**
     * Resolves the visual variant applied to the table head.
     */
    private function buildHeadClasses(): string
    {
        $variant = strtolower(trim($this->resolveOptionalString($this->headVariant)));

        return in_array($variant, ['dark', 'light'], true) ? 'table-' . $variant : '';
    }

    /**
     * Normalizes table headers and appends the actions column when needed.
     *
     * @return list<array{html:string,scope:string,classes:string}>
     */
    private function normalizeHeaders(bool $escape): array
    {
        if (! is_array($this->headers)) {
            return [];
        }

        $headers = [];

        foreach ($this->headers as $header) {
            if (is_object($header)) {
                $header = get_object_vars($header);
            }

            if (is_array($header)) {
                $label   = $this->resolveOptionalString($header['label'] ?? $header['text'] ?? $header['value'] ?? '');
                $scope   = $this->resolveNonEmptyString($header['scope'] ?? '', 'col');
                $classes = $this->normalizeClasses($this->resolveOptionalString($header['classes'] ?? ''));
            } else {
                $label   = $this->resolveOptionalString($header);
                $scope   = 'col';
                $classes = '';
            }

            if ($label === '') {
                continue;
            }

            $headers[] = [
                'html'    => $escape ? esc($label) : $label,
                'scope'   => $scope,
                'classes' => $classes,
            ];
        }

        if ($this->hasActions()) {
            $actionsHeader = $this->resolveOptionalString($this->actionsHeader);

            if ($actionsHeader !== '') {
                $headers[] = [
                    'html'    => $escape ? esc($actionsHeader) : $actionsHeader,
                    'scope'   => 'col',
                    'classes' => $this->normalizeClasses($this->resolveOptionalString($this->actionsCellClasses)),
                ];
            }
        }

        return $headers;
    }

    /**
     * Normalizes table rows, including optional action cells.
     *
     * @return list<array{
     *     classes:string,
     *     cells:list<array{tag:string,html:string,classes:string,scope:string,attributes:array<string,string>}>
     * }>
     */
    private function normalizeRows(bool $escape): array
    {
        if (! is_array($this->rows)) {
            return [];
        }

        $rows = [];

        foreach ($this->rows as $row) {
            if (is_object($row)) {
                $row = get_object_vars($row);
            }

            if (! is_array($row)) {
                continue;
            }

            $rowClasses = '';
            $rowCells   = $row;
            $actions    = [];

            if (array_key_exists('cells', $row) || array_key_exists('actions', $row) || array_key_exists('classes', $row)) {
                $rowCells   = is_array($row['cells'] ?? null) ? $row['cells'] : [];
                $actions    = is_array($row['actions'] ?? null) ? $row['actions'] : [];
                $rowClasses = $this->normalizeClasses($this->resolveOptionalString($row['classes'] ?? ''));
            }

            $cells = [];
            $headers = $this->normalizeHeaders(false);

            foreach (array_values($rowCells) as $index => $cell) {
                if (is_object($cell)) {
                    $cell = get_object_vars($cell);
                }

                if (is_array($cell)) {
                    $value   = $this->resolveOptionalString($cell['content'] ?? $cell['label'] ?? $cell['text'] ?? $cell['value'] ?? '');
                    $tag     = strtolower(trim($this->resolveOptionalString($cell['tag'] ?? 'td')));
                    $scope   = $this->resolveOptionalString($cell['scope'] ?? '');
                    $classes = $this->normalizeClasses($this->resolveOptionalString($cell['classes'] ?? ''));
                } else {
                    $value   = $this->resolveOptionalString($cell);
                    $tag     = 'td';
                    $scope   = '';
                    $classes = '';
                }

                if ($value === '') {
                    continue;
                }

                if (! in_array($tag, ['td', 'th'], true)) {
                    $tag = 'td';
                }

                $attributes = [];

                if ($this->normalizeBool($this->stacked) && $tag === 'td' && isset($headers[$index])) {
                    $attributes['data-label'] = strip_tags($headers[$index]['html']);
                }

                $cells[] = [
                    'tag'     => $tag,
                    'html'    => $escape ? esc($value) : $value,
                    'classes' => $classes,
                    'scope'   => $tag === 'th' ? $this->resolveNonEmptyString($scope, 'row') : '',
                    'attributes' => $attributes,
                ];
            }

            if ($actions !== []) {
                $actionAttributes = [];

                if ($this->normalizeBool($this->stacked) && $this->resolveOptionalString($this->actionsHeader) !== '') {
                    $actionAttributes['data-label'] = $this->resolveOptionalString($this->actionsHeader);
                }

                $cells[] = [
                    'tag'        => 'td',
                    'html'       => $this->renderActionsHtml($actions, $escape),
                    'classes'    => $this->normalizeClasses($this->resolveOptionalString($this->actionsCellClasses)),
                    'scope'      => '',
                    'attributes' => $actionAttributes,
                ];
            }

            if ($cells !== []) {
                $rows[] = [
                    'classes' => $rowClasses,
                    'cells'   => $cells,
                ];
            }
        }

        return $rows;
    }

    /**
     * Normalizes the table footer.
     *
     * @return list<array{html:string,classes:string}>
     */
    private function normalizeFooter(bool $escape): array
    {
        if (! is_array($this->footer)) {
            return [];
        }

        $footer = [];

        foreach ($this->footer as $cell) {
            if (is_object($cell)) {
                $cell = get_object_vars($cell);
            }

            if (is_array($cell)) {
                $value   = $this->resolveOptionalString($cell['content'] ?? $cell['label'] ?? $cell['text'] ?? $cell['value'] ?? '');
                $classes = $this->normalizeClasses($this->resolveOptionalString($cell['classes'] ?? ''));
            } else {
                $value   = $this->resolveOptionalString($cell);
                $classes = '';
            }

            if ($value === '') {
                continue;
            }

            $footer[] = [
                'html'    => $escape ? esc($value) : $value,
                'classes' => $classes,
            ];
        }

        return $footer;
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
     * Generates the HTML for a row action group.
     *
     * @param array<int, mixed> $actions
     */
    private function renderActionsHtml(array $actions, bool $escape): string
    {
        $html = '<div class="d-inline-flex gap-2">';

        foreach ($actions as $action) {
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

            $tag     = $this->resolveOptionalString($action['url'] ?? $action['href'] ?? '') !== '' ? 'a' : 'button';
            $variant = $this->normalizeVariant($this->resolveNonEmptyString($action['variant'] ?? $action['type'] ?? '', 'secondary'));
            $prefix  = $this->normalizeBool($action['outline'] ?? true, true) ? 'btn-outline-' : 'btn-';
            $classes = ['btn', $prefix . $variant, 'btn-sm'];
            $extra   = $this->normalizeClasses($this->resolveOptionalString($action['classes'] ?? ''));

            if ($extra !== '') {
                $classes[] = $extra;
            }

            $attributes = ' class="' . esc($this->normalizeClasses(implode(' ', $classes))) . '"';

            if ($tag === 'a') {
                $attributes .= ' href="' . esc($this->resolveOptionalString($action['url'] ?? $action['href'] ?? '')) . '"';
            } else {
                $attributes .= ' type="' . esc($this->resolveNonEmptyString($action['buttonType'] ?? '', 'button')) . '"';
            }

            if ($this->normalizeBool($action['disabled'] ?? false)) {
                $attributes .= $tag === 'a'
                    ? ' aria-disabled="true" tabindex="-1"'
                    : ' disabled="disabled"';
            }

            $html .= '<' . $tag . $attributes . '>' . ($escape ? esc($label) : $label) . '</' . $tag . '>';
        }

        return $html . '</div>';
    }

    /**
     * Prepares the empty-state row shown when no data is available.
     *
     * @return array{messageHtml:string,classes:string,colspan:int}
     */
    private function normalizeEmptyRow(bool $escape): array
    {
        $headers = $this->normalizeHeaders(false);
        $colspan = max(1, count($headers));
        $message = $this->resolveOptionalString($this->emptyMessage);

        return [
            'messageHtml' => $escape ? esc($message) : $message,
            'classes'     => $this->normalizeClasses($this->resolveOptionalString($this->emptyClasses)),
            'colspan'     => max(1, $colspan),
        ];
    }

    /**
     * Indicates whether at least one row provides action items.
     */
    private function hasActions(): bool
    {
        if (! is_array($this->rows)) {
            return false;
        }

        foreach ($this->rows as $row) {
            if (is_object($row)) {
                $row = get_object_vars($row);
            }

            if (! is_array($row)) {
                continue;
            }

            if (is_array($row['actions'] ?? null) && $row['actions'] !== []) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validates a Bootstrap variant or returns an empty string.
     */
    private function normalizeVariant(string $variant): string
    {
        $variant = strtolower(trim($variant));

        if ($variant === '') {
            return '';
        }

        return preg_match('/^[a-z0-9_-]+$/', $variant) === 1 ? $variant : '';
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
