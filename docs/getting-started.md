# Getting Started

Use this guide for installation, asset publishing, and a first Bootstrap Cell rendered in a CodeIgniter 4 view.

## Installation

Install the package in your CodeIgniter 4 project:

```bash
composer require domprojects/codeigniter4-bootstrap
```

## Optional Automation Plugin

If you also want automatic publication after `composer install` and `composer update`, install the optional companion plugin:

```bash
composer require domprojects/codeigniter4-bootstrap-plugin
```

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

Destination:

```text
public/assets/bootstrap
```

## Usage in Views

Include Bootstrap assets:

```php
<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
```

Render a first Cell:

```php
<?= view_cell(\domProjects\CodeIgniterBootstrap\Cells\AlertCell::class, [
    'message' => 'Profile updated successfully.',
    'variant' => 'success',
]) ?>
```

## Next Steps

- [Cells Reference](cells/index.md)
- [Helpers Reference](helpers.md)
- [Documentation Index](index.md)
