# domProjects CodeIgniter 4 Bootstrap

Bootstrap 5 asset publisher for CodeIgniter 4.

This package adds a Spark command that publishes the Bootstrap distribution files from Composer dependencies into your public directory.

## Features

- Installs Bootstrap with Composer
- Publishes only the Bootstrap production assets
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

## Package Structure

```text
src/
  Commands/
    PublishBootstrap.php
  Publishers/
    BootstrapPublisher.php
```

## Local Development

If you want to work on the package locally from another project, you can use a Composer `path` repository.

Example:

```json
{
    "repositories": {
        "domprojects-codeigniter4-bootstrap": {
            "type": "path",
            "url": "packages/domprojects/codeigniter4-bootstrap",
            "options": {
                "symlink": false
            }
        }
    }
}
```

If you also test the automation plugin locally, add a second `path` repository for `packages/domprojects/codeigniter4-bootstrap-plugin`.

## Related Package

- `domprojects/codeigniter4-bootstrap-plugin`: optional Composer plugin for automatic publication

## License

MIT
