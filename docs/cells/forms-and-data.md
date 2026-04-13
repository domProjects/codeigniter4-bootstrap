# Cells Reference: Forms and Data Display

Use this guide for validation-aware forms, input groups, feedback blocks, and table rendering.

## Form Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\FormCell::class, [
    'action' => '/contact',
    'items'  => [
        ['label' => 'Name', 'id' => 'contactName', 'name' => 'name'],
        ['type' => 'textarea', 'label' => 'Message', 'id' => 'contactMessage'],
        ['type' => 'submit', 'label' => 'Send'],
    ],
]) ?>
```

Supported parameters:

- `action`: form action URL
- `method`: form method, defaults to `post`
- `items`: form fields using `type`, `label`, `id`, `name`, `value`, `help`, `classes`, and more
- `classes`: additional CSS classes on the `<form>`
- `id`: optional form id
- `enctype`: optional form enctype like `multipart/form-data`
- `novalidate`: adds the `novalidate` attribute
- `validated`: adds `.was-validated` on the `<form>`
- `autocomplete`: optional form autocomplete value
- `disabled`: wraps all controls in a disabled `<fieldset>`
- `escape`: escapes labels, help text, and values by default; set to `false` to render trusted HTML

Supported item types:

- `input`: default text-like input, with `inputType` for `email`, `password`, `file`, and more
- `textarea`: textarea control with optional `rows`
- `select`: select control with `options`
- `checkbox`: Bootstrap checkbox
- `radio`: Bootstrap radio
- `switch`: Bootstrap switch
- `hidden`: hidden input without wrapper markup
- `button`, `submit`, `reset`: Bootstrap button actions

Useful item options:

- `floating`: wraps `input`, `textarea`, or `select` inside `.form-floating`
- `state`: `valid` or `invalid`
- `validFeedback`: renders `.valid-feedback`
- `invalidFeedback`: renders `.invalid-feedback`
- `help`: renders `.form-text`
- `column`: shorthand like `6`, `12`, `md-6`, or `auto`
- `columnClasses`: explicit Bootstrap column classes like `col-md-6`
- `inline`: renders inline checkboxes or radios
- `reverse`: renders checks or radios with `.form-check-reverse`
- `wrapperClasses`, `labelClasses`, `helpClasses`, `validFeedbackClasses`, `invalidFeedbackClasses`: customize wrappers and feedback classes

For more advanced layouts, combine `classes="row g-3"` on the form with `column` or `columnClasses` on each field item.

## Input Group Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\InputGroupCell::class, [
    'items' => [
        ['type' => 'text', 'content' => '@'],
        ['type' => 'input', 'name' => 'username', 'placeholder' => 'Username'],
    ],
]) ?>
```

Supported parameters:

- `items`: ordered segments for the group
- `size`: `sm` or `lg`
- `classes`: additional CSS classes on the `.input-group`
- `id`: optional group id
- `escape`: escapes text content and option labels by default; set to `false` to render trusted HTML

Supported item types:

- `text`, `addon`, `label`: render `.input-group-text`
- `input`: render a `.form-control` input with `inputType`
- `textarea`: render a `.form-control` textarea
- `select`: render a `.form-select` with `options`
- `checkbox`, `radio`: render check controls inside `.input-group-text`
- `button`: render a Bootstrap button segment

Useful item options:

- `floating`: wraps `input`, `textarea`, or `select` inside `.form-floating`
- `label`: floating label text for wrapped controls
- `state`: `valid` or `invalid`
- `validFeedback`: renders `.valid-feedback`
- `invalidFeedback`: renders `.invalid-feedback`
- `wrapperClasses`, `labelClasses`, `validFeedbackClasses`, `invalidFeedbackClasses`: customize wrappers and feedback blocks

When validation is used on one or more items, `InputGroupCell` automatically adds `.has-validation` to the group wrapper.

## Validation Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ValidationCell::class, [
    'message' => 'Please provide a valid city.',
]) ?>
```

Supported parameters:

- `message`: alias of `content`
- `content`: feedback content
- `state`: `valid` or `invalid`, defaults to `invalid`
- `type`: alias of `state`
- `tooltip`: switches from `*-feedback` to `*-tooltip`
- `classes`: additional CSS classes on the feedback block
- `id`: optional explicit id
- `escape`: escapes content by default; set to `false` to render trusted HTML

## Table Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\TableCell::class, [
    'headers' => ['#', 'Name', 'Email'],
    'rows'    => [
        [
            ['tag' => 'th', 'content' => '1'],
            'Jane Doe',
            'jane@example.com',
        ],
    ],
]) ?>
```

Supported parameters:

- `headers`: header cells as strings or arrays using `label`, `scope`, and `classes`
- `rows`: body rows as arrays of values, cell arrays using `content`, `tag`, `scope`, and `classes`, or structured rows using `cells`, `actions`, and `classes`
- `footer`: optional footer row cells
- `caption`: optional table caption
- `captionTop`: renders `.caption-top`
- `variant`: contextual table variant like `dark`, `primary`, or `success`
- `striped`, `stripedColumns`, `hover`, `bordered`, `borderless`, `small`: Bootstrap table modifiers
- `responsive`: wraps the table in `.table-responsive`
- `responsiveBreakpoint`: breakpoint suffix like `sm`, `md`, or `lg`
- `stacked`: adds `data-label` hooks and helper classes for stacked mobile table patterns
- `stackedBreakpoint`: breakpoint suffix for the stacked helper wrapper
- `emptyMessage`: message shown when there are no rows
- `emptyClasses`: classes on the empty-state cell
- `actionsHeader`: header label for row actions, defaults to `Actions`
- `actionsCellClasses`: classes on generated action cells
- `classes`: additional CSS classes on the `<table>`
- `wrapperClasses`: additional CSS classes on the responsive wrapper
- `headVariant`: `light` or `dark` for `<thead>`
- `escape`: escapes header and cell content by default; set to `false` to render trusted HTML

Structured rows may also define:

- `cells`: ordered row cells
- `actions`: action buttons rendered in a dedicated actions column
- `classes`: classes applied to the `<tr>`

## See Also

- [Cells Reference](index.md)
- [Core Components](core-components.md)
- [Overlays and Navigation](overlays-and-navigation.md)
- [Helpers Reference](../helpers.md)
- [Documentation Index](../index.md)
