# Cells Reference: Core Components

Use this guide for foundational Bootstrap Cells and simple data display components.

## Alert Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\AlertCell::class, [
    'message' => 'Profile updated successfully.',
    'variant' => 'success',
]) ?>
```

Supported parameters:

- `message`: alias of `content` for simple text alerts
- `content`: alert body content
- `heading`: optional `.alert-heading`
- `variant`: Bootstrap contextual variant like `primary`, `success`, `warning`, `danger`
- `type`: alias of `variant`
- `dismissible`: adds the close button and Bootstrap dismissal classes
- `escape`: escapes `heading` and `content` by default; set to `false` to render trusted HTML
- `classes`: additional CSS classes on the wrapper
- `role`: ARIA role, defaults to `alert`
- `closeLabel`: accessible label for the dismiss button, defaults to `Close`

## Badge Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\BadgeCell::class, [
    'content' => '99+',
    'variant' => 'danger',
    'pill'    => true,
]) ?>
```

Supported parameters:

- `message`: alias of `content`
- `content`: badge content
- `variant`: Bootstrap contextual variant like `primary`, `secondary`, `success`, `danger`
- `type`: alias of `variant`
- `pill`: adds the `.rounded-pill` utility class
- `classes`: additional CSS classes on the badge
- `escape`: escapes `content` and `hiddenText` by default; set to `false` to render trusted HTML
- `hiddenText`: optional visually hidden text for accessibility

## Button Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ButtonCell::class, [
    'content' => 'View profile',
    'href'    => '/profile',
    'variant' => 'secondary',
    'outline' => true,
]) ?>
```

Supported parameters:

- `message`: alias of `content`
- `content`: button label/content
- `variant`: Bootstrap contextual variant like `primary`, `secondary`, `success`, `danger`
- `type`: alias of `variant`
- `outline`: switches from `.btn-{variant}` to `.btn-outline-{variant}`
- `size`: `sm` or `lg`
- `href`: when set, renders an anchor button
- `tag`: force `button` or `a`
- `buttonType`: `type` attribute for `<button>`, defaults to `button`
- `disabled`: disabled state for buttons and accessible disabled state for anchors
- `classes`: additional CSS classes
- `escape`: escapes `content` by default; set to `false` to render trusted HTML
- `role`: optional explicit role

## Card Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\CardCell::class, [
    'title'   => 'Featured',
    'content' => 'This is a wider card with supporting text.',
]) ?>
```

Supported parameters:

- `message`: alias of `content`
- `content`: main card text rendered in `.card-text`
- `title`: optional `.card-title`
- `subtitle`: optional `.card-subtitle`
- `header`: optional `.card-header`
- `footer`: optional `.card-footer`
- `image`: optional image URL
- `imageAlt`: alt text for the image
- `imagePosition`: `top` or `bottom`
- `classes`: additional CSS classes on the card wrapper
- `bodyClasses`: additional CSS classes on `.card-body`
- `titleTag`: heading tag for the title, defaults to `h5`
- `escape`: escapes textual content by default; set to `false` to render trusted HTML

## Image Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ImageCell::class, [
    'src'       => '/images/photo.jpg',
    'alt'       => 'Profile photo',
    'thumbnail' => true,
    'rounded'   => true,
]) ?>
```

Supported parameters:

- `src`: image source URL
- `image`: alias of `src`
- `alt`: image alt text
- `imageAlt`: alias of `alt`
- `fluid`: adds `.img-fluid`, enabled by default
- `thumbnail`: adds `.img-thumbnail`
- `rounded`: `true` for `.rounded` or a custom rounded utility class like `rounded-circle`
- `align`: `start`, `end`, or `center`
- `classes`: additional CSS classes on the `<img>`
- `attrs`: additional `<img>` attributes such as `loading`, `width`, `height`, or `data-*`
- `sources`: optional `<source>` definitions for wrapping the image in a `<picture>` element

## Figure Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\FigureCell::class, [
    'src'          => '/images/photo.jpg',
    'alt'          => 'Profile photo',
    'caption'      => 'A caption for the above image.',
    'captionAlign' => 'end',
]) ?>
```

Supported parameters:

- `src`: figure image source URL
- `image`: alias of `src`
- `alt`: image alt text
- `imageAlt`: alias of `alt`
- `caption`: figure caption text
- `content`: alias of `caption`
- `message`: alias of `caption`
- `fluid`: adds `.img-fluid` to the figure image, enabled by default
- `thumbnail`: adds `.img-thumbnail` to the figure image
- `rounded`: `true` for `.rounded` or a custom rounded utility class like `rounded-circle`
- `captionAlign`: `start`, `center`, or `end`
- `classes`: additional CSS classes on the `<figure>`
- `imageClasses`: additional CSS classes on the `<img>`
- `captionClasses`: additional CSS classes on the `<figcaption>`
- `attrs`: additional `<img>` attributes such as `loading`, `width`, `height`, or `data-*`
- `sources`: optional `<source>` definitions for wrapping the image in a `<picture>` element
- `escape`: escapes caption content by default; set to `false` to render trusted HTML

## Progress Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ProgressCell::class, [
    'value' => 25,
    'label' => '25%',
]) ?>
```

Supported parameters:

- `value`: shorthand single progress value
- `label`: shorthand single progress label
- `bars`: array of bars using `value`, `label`, `variant`, `striped`, `animated`, and `classes`
- `height`: optional inline height
- `classes`: additional CSS classes on the wrapper
- `escape`: escapes labels by default; set to `false` to render trusted HTML

## Spinner Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\SpinnerCell::class, [
    'variant' => 'primary',
]) ?>
```

Supported parameters:

- `type`: `border` or `grow`
- `variant`: optional contextual text color like `primary` or `secondary`
- `size`: `sm` for the small spinner variant
- `label`: accessible loading label, defaults to `Loading...`
- `classes`: additional CSS classes

## Placeholder Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\PlaceholderCell::class, [
    'items' => [
        ['width' => 8],
        ['width' => 6, 'variant' => 'secondary'],
    ],
]) ?>
```

Supported parameters:

- `items`: placeholder rows using `width`, `variant`, `size`, and `classes`
- `width`: shorthand width for the single default placeholder
- `variant`: optional background variant like `primary`, `secondary`, or `success`
- `size`: `lg`, `sm`, or `xs`
- `animation`: `glow` or `wave`
- `classes`: additional CSS classes on the wrapper
- `itemClasses`: additional CSS classes on the single placeholder item

## Description List Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\DescriptionListCell::class, [
    'items' => [
        ['term' => 'Name', 'description' => 'Jane Doe'],
        ['term' => 'Role', 'description' => 'Admin'],
    ],
]) ?>
```

Supported parameters:

- `items`: list entries using `term`, `description`, `termClasses`, and `descriptionClasses`
- `classes`: additional classes on the `<dl>`
- `row`: adds Bootstrap `.row`, enabled by default
- `termClasses`: default classes for `<dt>`, defaults to `col-sm-3`
- `descriptionClasses`: default classes for `<dd>`, defaults to `col-sm-9`
- `escape`: escapes terms and descriptions by default; set to `false` to render trusted HTML

## Stats Cards Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\StatsCardsCell::class, [
    'items' => [
        ['label' => 'Users', 'value' => '128'],
        ['label' => 'Revenue', 'value' => '$4.2k', 'description' => 'This month'],
    ],
]) ?>
```

Supported parameters:

- `items`: cards using `label`, `value`, `description`, `meta`, `variant`, `columnClasses`, and `cardClasses`
- `classes`: wrapper classes, defaults to `row g-3`
- `columnClasses`: default classes for each item column
- `cardClasses`: default classes for each card
- `escape`: escapes content by default; set to `false` to render trusted HTML

## Empty State Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\EmptyStateCell::class, [
    'title'   => 'No projects',
    'content' => 'Create your first project to get started.',
]) ?>
```

Supported parameters:

- `title`: heading text, defaults to `Nothing here yet`
- `content`: description text
- `message`: alias of `content`
- `actions`: optional action buttons using `label`, `href`, `variant`, and `classes`
- `classes`: wrapper classes
- `bodyClasses`: classes on the inner content wrapper
- `maxWidth`: max-width style for the content wrapper
- `escape`: escapes content by default; set to `false` to render trusted HTML

## See Also

- [Cells Reference](index.md)
- [Overlays and Navigation](overlays-and-navigation.md)
- [Forms and Data Display](forms-and-data.md)
- [Documentation Index](../index.md)
