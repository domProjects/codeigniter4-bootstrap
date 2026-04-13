# Cells Reference: Overlays and Navigation

Use this guide for Bootstrap Cells related to navigation, overlays, dismissal, and interactive UI patterns.

## Breadcrumb Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\BreadcrumbCell::class, [
    'items' => [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Library', 'url' => '/library'],
        ['label' => 'Data', 'active' => true],
    ],
]) ?>
```

Supported parameters:

- `items`: array of breadcrumb items using `label`, `url` or `href`, optional `active`, `classes`, `linkClasses`, and `current`
- `classes`: additional CSS classes on the breadcrumb list
- `listClasses`: alias for additional classes on the breadcrumb list
- `itemClasses`: shared classes added to each `.breadcrumb-item`
- `linkClasses`: shared classes added to breadcrumb links
- `divider`: custom divider string or CSS value for `--bs-breadcrumb-divider`
- `current`: default `aria-current` value for the active item, defaults to `page`
- `label`: `aria-label` for the wrapping `<nav>`, defaults to `breadcrumb`
- `escape`: escapes item labels by default; set to `false` to render trusted HTML

## Pagination Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\PaginationCell::class, [
    'currentPage' => 3,
    'totalPages'  => 10,
    'urlPattern'  => '/users?page={page}',
]) ?>
```

Supported parameters:

- `items`: optional manual page items using `label`, `url` or `href`, `active`, `disabled`, `ariaLabel`, `classes`, `linkClasses`, and `ellipsis`
- `currentPage`: current page number for generated pagination
- `totalPages`: total page count for generated pagination
- `urlPattern`: generated URL pattern with `{page}`, defaults to `?page={page}`
- `showPreviousNext`: shows previous and next controls in generated mode
- `showFirstLast`: shows first and last controls in generated mode
- `previousLabel`: previous control label
- `nextLabel`: next control label
- `firstLabel`: first-page control label
- `lastLabel`: last-page control label
- `window`: number of pages shown around the current page in generated mode
- `size`: `sm` or `lg`
- `align`: `center` or `end`
- `classes`: additional CSS classes on the pagination wrapper
- `itemClasses`: shared classes added to each `.page-item`
- `linkClasses`: shared classes added to each `.page-link`
- `label`: `aria-label` for the wrapping `<nav>`
- `escape`: escapes labels by default; set to `false` to render trusted HTML

## Navbar Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\NavbarCell::class, [
    'brandLabel' => 'Demo',
    'brandUrl'   => '/',
    'items'      => [
        ['label' => 'Home', 'url' => '/', 'active' => true],
        ['label' => 'Features', 'url' => '/features'],
    ],
]) ?>
```

Supported parameters:

- `brandLabel`: navbar brand label
- `brandUrl`: brand link URL
- `items`: array of nav items using `label`, optional `url` or `href`, `active`, `disabled`, and `classes`
- `expand`: collapse breakpoint, defaults to `lg`
- `background`: background utility suffix or full class, defaults to `body-tertiary`
- `theme`: optional `light` or `dark`, rendered as `data-bs-theme`
- `container`: `fluid`, `default`, empty string, or a custom container class
- `classes`: additional CSS classes on the `<nav>`
- `navClasses`: additional CSS classes on the `<ul class="navbar-nav">`
- `content`: optional extra content rendered inside the collapsed navbar area
- `collapseId`: optional explicit collapse id
- `escape`: escapes brand, nav labels, and `content` by default; set to `false` to render trusted HTML

## Dropdown Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\DropdownCell::class, [
    'content' => 'Actions',
    'items'   => [
        ['label' => 'Profile', 'url' => '/profile'],
        ['divider' => true],
        ['label' => 'Settings'],
    ],
]) ?>
```

Supported parameters:

- `message`: alias of `content`
- `content`: toggle label/content
- `variant`: Bootstrap contextual variant like `secondary`, `primary`, `danger`
- `type`: alias of `variant`
- `outline`: switches from `.btn-{variant}` to `.btn-outline-{variant}`
- `size`: `sm` or `lg`
- `direction`: `dropup`, `dropend`, `dropstart`, `dropup-center`, or `dropdown-center`
- `align`: `end`
- `dark`: adds `.dropdown-menu-dark`
- `classes`: additional CSS classes on the dropdown wrapper
- `buttonClasses`: additional CSS classes on the toggle button
- `menuClasses`: additional CSS classes on the menu
- `id`: optional explicit toggle id
- `items`: array of menu entries using `label`, optional `url` or `href`, `disabled`, `active`, `divider`, `header`, `text`, and `classes`
- `escape`: escapes labels and text by default; set to `false` to render trusted HTML

## Accordion Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\AccordionCell::class, [
    'items' => [
        ['title' => 'Item 1', 'content' => 'Body 1', 'active' => true],
        ['title' => 'Item 2', 'content' => 'Body 2'],
    ],
]) ?>
```

Supported parameters:

- `items`: array of accordion items using `title` or `label`, `content` or `body`, optional `active`, and `classes`
- `flush`: adds `.accordion-flush`
- `alwaysOpen`: removes the shared `data-bs-parent` behavior
- `classes`: additional CSS classes on the accordion wrapper
- `id`: optional explicit accordion id
- `escape`: escapes titles and content by default; set to `false` to render trusted HTML

## Modal Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ModalCell::class, [
    'title'   => 'Confirm delete',
    'content' => 'Are you sure?',
]) ?>
```

Supported parameters:

- `title`: modal title
- `message`: alias of `content`
- `content`: modal body content
- `footer`: optional footer markup/content
- `classes`: additional CSS classes on `.modal`
- `dialogClasses`: additional CSS classes on `.modal-dialog`
- `size`: `sm`, `lg`, `xl`, or `fullscreen`
- `centered`: adds `.modal-dialog-centered`
- `scrollable`: adds `.modal-dialog-scrollable`
- `fade`: includes the `.fade` class, enabled by default
- `show`: adds `.show` and inline display styling
- `closeButton`: renders the dismiss button in the header
- `staticBackdrop`: adds `data-bs-backdrop="static"`
- `keyboard`: set to `false` to render `data-bs-keyboard="false"`
- `id`: optional explicit modal id
- `escape`: escapes title, body, and footer by default; set to `false` to render trusted HTML

## Tabs Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\TabsCell::class, [
    'items' => [
        ['label' => 'Home', 'content' => 'Home content', 'active' => true],
        ['label' => 'Profile', 'content' => 'Profile content'],
    ],
]) ?>
```

Supported parameters:

- `items`: array of tab items using `label` or `title`, `content`, optional `active`, and `disabled`
- `pills`: switches from tabs to pills
- `fill`: adds `.nav-fill`
- `justified`: adds `.nav-justified`
- `fade`: uses fading tab panes
- `classes`: additional CSS classes on the nav wrapper
- `navClasses`: additional CSS classes merged into the nav element
- `contentClasses`: additional CSS classes on `.tab-content`
- `id`: optional explicit base id
- `escape`: escapes labels and content by default; set to `false` to render trusted HTML

## Toast Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ToastCell::class, [
    'title'   => 'Notification',
    'content' => 'Hello world',
]) ?>
```

Supported parameters:

- `title`: optional toast header title
- `subtitle`: optional secondary header text
- `message`: alias of `content`
- `content`: toast body content
- `classes`: additional CSS classes on `.toast`
- `bodyClasses`: additional CSS classes on `.toast-body`
- `headerClasses`: additional CSS classes on `.toast-header`
- `autoHide`: controls `data-bs-autohide`, enabled by default
- `delay`: delay in milliseconds, defaults to `5000`
- `fade`: includes the `.fade` class, enabled by default
- `show`: adds `.show`
- `closeButton`: renders the dismiss button in the header
- `role`: ARIA role, defaults to `alert`
- `ariaLive`: defaults to `assertive`
- `ariaAtomic`: defaults to `true`
- `escape`: escapes title, subtitle, and body by default; set to `false` to render trusted HTML

## Offcanvas Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\OffcanvasCell::class, [
    'title'   => 'Menu',
    'content' => 'Sidebar content',
]) ?>
```

Supported parameters:

- `title`: offcanvas title
- `message`: alias of `content`
- `content`: offcanvas body content
- `placement`: `start`, `end`, `top`, or `bottom`
- `responsive`: responsive variant like `lg` for `offcanvas-lg`
- `classes`: additional CSS classes on the offcanvas wrapper
- `bodyClasses`: additional CSS classes on `.offcanvas-body`
- `headerClasses`: additional CSS classes on `.offcanvas-header`
- `show`: adds `.show`
- `scroll`: controls `data-bs-scroll`
- `backdrop`: `true`, `false`, or `static`
- `closeButton`: renders the dismiss button
- `theme`: optional `data-bs-theme` value
- `id`: optional explicit offcanvas id
- `escape`: escapes title and body by default; set to `false` to render trusted HTML

## Collapse Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\CollapseCell::class, [
    'content' => 'Collapsed content',
    'card'    => true,
]) ?>
```

Supported parameters:

- `message`: alias of `content`
- `content`: collapse content
- `show`: adds `.show`
- `horizontal`: adds `.collapse-horizontal`
- `card`: wraps the content in `.card.card-body`
- `classes`: additional CSS classes on the outer collapse wrapper
- `bodyClasses`: additional CSS classes on the wrapped inner body
- `id`: optional explicit collapse id
- `escape`: escapes content by default; set to `false` to render trusted HTML

## Carousel Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\CarouselCell::class, [
    'items' => [
        ['src' => '/img/slide-1.jpg', 'alt' => 'Slide 1', 'active' => true],
        ['src' => '/img/slide-2.jpg', 'alt' => 'Slide 2'],
    ],
]) ?>
```

Supported parameters:

- `items`: array of slides using `src` or `image`, `alt`, optional `title`, `caption`, `active`, `interval`, and `captionClasses`
- `controls`: renders prev/next controls
- `indicators`: renders slide indicators
- `fade`: adds `.carousel-fade`
- `dark`: adds `.carousel-dark`
- `ride`: enables automatic ride
- `touch`: controls touch swipe support
- `wrap`: controls cycling from last to first
- `interval`: default interval in milliseconds
- `classes`: additional CSS classes on the carousel
- `id`: optional explicit carousel id
- `escape`: escapes captions by default; set to `false` to render trusted HTML

## Popover Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\PopoverCell::class, [
    'content' => 'Open popover',
    'title'   => 'Popover title',
    'body'    => 'Popover body',
]) ?>
```

Supported parameters:

- `message`: alias of the visible trigger `content`
- `content`: trigger label/content
- `title`: popover title
- `body`: popover body
- `placement`: `top`, `bottom`, `start`, or `end`
- `trigger`: custom Bootstrap trigger string
- `html`: adds `data-bs-html="true"`
- `variant`: contextual button variant, defaults to `secondary`
- `type`: alias of `variant`
- `outline`: uses outline button styling
- `size`: `sm` or `lg`
- `href`: when set, renders an anchor trigger
- `tag`: force `button` or `a`
- `buttonType`: button `type` attribute, defaults to `button`
- `disabled`: disabled state with accessibility attributes
- `classes`: additional CSS classes on the trigger
- `id`: optional explicit id
- `escape`: escapes title, body, and label by default; set to `false` to render trusted HTML

## Tooltip Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\TooltipCell::class, [
    'content' => 'Hover me',
    'title'   => 'Tooltip text',
]) ?>
```

Supported parameters:

- `message`: alias of the visible trigger `content`
- `content`: trigger label/content
- `title`: tooltip text
- `placement`: `top`, `bottom`, `start`, or `end`
- `trigger`: custom Bootstrap trigger string
- `html`: adds `data-bs-html="true"`
- `variant`: contextual button variant, defaults to `secondary`
- `type`: alias of `variant`
- `outline`: uses outline button styling
- `size`: `sm` or `lg`
- `href`: when set, renders an anchor trigger
- `tag`: force `button` or `a`
- `buttonType`: button `type` attribute, defaults to `button`
- `disabled`: disabled state with accessibility attributes
- `classes`: additional CSS classes on the trigger
- `id`: optional explicit id
- `escape`: escapes title and label by default; set to `false` to render trusted HTML

## Scrollspy Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ScrollspyCell::class, [
    'id'    => 'docsScrollspy',
    'items' => [
        ['label' => 'Intro', 'content' => 'Introduction section'],
        ['label' => 'Usage', 'content' => 'Usage section'],
    ],
]) ?>
```

Supported parameters:

- `items`: sections using `label`, `title`, `content`, `id`, `headingLevel`, and `classes`
- `id`: optional nav id used by `data-bs-target`
- `navType`: `nav` or `list-group`
- `navVariant`: `pills` or `tabs` when `navType` is `nav`
- `height`: scrollable content height, defaults to `260px`
- `rootMargin`: Bootstrap scrollspy root margin, defaults to `0px 0px -40%`
- `classes`: additional CSS classes on the outer wrapper
- `navClasses`: additional CSS classes on the nav container
- `contentClasses`: additional CSS classes on the scrollable content area
- `escape`: escapes labels, headings, and content by default; set to `false` to render trusted HTML

## Close Button Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\CloseButtonCell::class, [
    'dismiss' => 'alert',
]) ?>
```

Supported parameters:

- `label`: ARIA label, defaults to `Close`
- `classes`: additional CSS classes on the button
- `dismiss`: optional Bootstrap dismiss target like `alert`, `modal`, `offcanvas`, or `toast`
- `target`: optional `data-bs-target`
- `type`: button type, defaults to `button`
- `disabled`: disables the button
- `theme`: `light` or `dark`
- `id`: optional explicit id

## Button Group Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ButtonGroupCell::class, [
    'items' => [
        ['label' => 'Left'],
        ['label' => 'Middle', 'active' => true],
        ['label' => 'Right'],
    ],
]) ?>
```

Supported parameters:

- `items`: button definitions using `label`, `content`, `variant`, `outline`, `href`, `active`, `disabled`, and `classes`
- `vertical`: uses `.btn-group-vertical`
- `size`: `sm` or `lg`
- `label`: ARIA label for the group, defaults to `Button group`
- `role`: wrapper role, defaults to `group`
- `classes`: additional CSS classes on the wrapper
- `escape`: escapes button labels by default; set to `false` to render trusted HTML

## Button Toolbar Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\ButtonToolbarCell::class, [
    'groups' => [
        ['items' => [['label' => '1'], ['label' => '2']]],
        ['items' => [['label' => '3']]],
    ],
]) ?>
```

Supported parameters:

- `groups`: array of button groups using `items`, `label`, `vertical`, `size`, and `classes`
- `label`: ARIA label for the toolbar, defaults to `Button toolbar`
- `role`: wrapper role, defaults to `toolbar`
- `classes`: additional CSS classes on the toolbar
- `escape`: escapes button labels by default; set to `false` to render trusted HTML

## Nav Cell

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\NavCell::class, [
    'variant' => 'pills',
    'items'   => [
        ['label' => 'Home', 'url' => '/', 'active' => true],
        ['label' => 'Profile', 'url' => '/profile'],
    ],
]) ?>
```

Supported parameters:

- `items`: nav items using `label`, `url`, `content`, `active`, `disabled`, `toggle`, and `classes`
- `variant`: `tabs`, `pills`, `underline`, or plain `nav`
- `fill`: adds `.nav-fill`
- `justified`: adds `.nav-justified`
- `vertical`: adds `.flex-column`
- `fade`: fades tab panes when content is provided
- `classes`: additional CSS classes on the nav
- `contentClasses`: additional CSS classes on the `.tab-content`
- `id`: optional base id used for generated tabs and panes
- `escape`: escapes labels and content by default; set to `false` to render trusted HTML

## See Also

- [Cells Reference](index.md)
- [Core Components](core-components.md)
- [Forms and Data Display](forms-and-data.md)
- [Documentation Index](../index.md)
