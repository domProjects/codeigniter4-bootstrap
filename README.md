# Bootstrap 5 for CodeIgniter 4

[![Packagist](https://img.shields.io/packagist/v/domprojects/codeigniter4-bootstrap?label=Packagist)](https://packagist.org/packages/domprojects/codeigniter4-bootstrap)
[![License](https://img.shields.io/github/license/domProjects/codeigniter4-bootstrap)](https://github.com/domProjects/codeigniter4-bootstrap/blob/main/LICENSE)
[![PHPUnit](https://img.shields.io/github/actions/workflow/status/domProjects/codeigniter4-bootstrap/phpunit.yml?branch=main&label=PHPUnit)](https://github.com/domProjects/codeigniter4-bootstrap/actions/workflows/phpunit.yml)
[![Psalm](https://img.shields.io/github/actions/workflow/status/domProjects/codeigniter4-bootstrap/psalm.yml?branch=main&label=Psalm)](https://github.com/domProjects/codeigniter4-bootstrap/actions/workflows/psalm.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/domProjects/codeigniter4-bootstrap/phpstan.yml?branch=main&label=PHPStan)](https://github.com/domProjects/codeigniter4-bootstrap/actions/workflows/phpstan.yml)

Bootstrap 5 tools for CodeIgniter 4.

This package currently provides:

- a Spark command that publishes the Bootstrap distribution files from Composer dependencies into your public directory
- reusable CodeIgniter 4 Cells for Bootstrap components
- CI4 helpers for validation-aware form payloads and table payload generation

## Features

- Installs Bootstrap with Composer
- Publishes only the Bootstrap production assets
- Provides reusable Bootstrap component Cells for CodeIgniter 4 views
- Includes helper functions for forms, validation, and tables
- Works with CodeIgniter 4 auto-discovery
- Keeps Bootstrap asset publication explicit and framework-oriented

## Requirements

- PHP 8.2 or newer
- CodeIgniter 4.7.2 or newer
- Bootstrap 5.3.8 or newer within the 5.3 branch

## Installation

Install the package in your CodeIgniter 4 project:

```bash
composer require domprojects/codeigniter4-bootstrap
```

## Optional Automation Plugin

If you also want automatic publication after `composer install` and `composer update`, install the companion plugin:

```bash
composer require domprojects/codeigniter4-bootstrap-plugin
```

The plugin package is optional. This main package works on its own.

## Spark Command

Publish assets manually:

```bash
php spark assets:publish-bootstrap
```

Force overwrite existing files:

```bash
php spark assets:publish-bootstrap --force
```

## Published Files

The package publishes these files:

- `css/bootstrap.min.css`
- `css/bootstrap.min.css.map`
- `js/bootstrap.bundle.min.js`
- `js/bootstrap.bundle.min.js.map`

## Destination

Files are published to:

```text
public/assets/bootstrap
```

## Usage in Views

Example in a CodeIgniter view:

```php
<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
```

## Documentation

Detailed documentation now lives in the `docs/` directory:

- [Documentation Index](docs/index.md)
- [Getting Started](docs/getting-started.md)
- [Cells Reference](docs/cells/index.md)
- [Cells Reference: Core Components](docs/cells/core-components.md)
- [Cells Reference: Overlays and Navigation](docs/cells/overlays-and-navigation.md)
- [Cells Reference: Forms and Data Display](docs/cells/forms-and-data.md)
- [Helpers Reference](docs/helpers.md)
- [Development Guide](docs/development.md)

## Included Cells

The package currently includes reusable Cells for:

- alerts, badges, buttons, cards, images, figures, breadcrumbs, lists, and pagination
- navigation and overlays such as navbars, dropdowns, tabs, accordions, modals, toasts, and offcanvas panels
- utility components such as progress bars, spinners, placeholders, popovers, tooltips, and scrollspy blocks
- forms, input groups, validation feedback, tables, and data display components

See the `docs/` directory for the full component reference and examples.

## Related Package

- `domprojects/codeigniter4-bootstrap-plugin`: optional Composer plugin for automatic publication

## License

MIT
