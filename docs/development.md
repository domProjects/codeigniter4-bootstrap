# Development Guide

Use this guide when you work on the package locally or maintain it alongside related packages.

## Package Structure

```text
src/
  Cells/
  Helpers/
  Commands/
  Publishers/
tests/
docs/
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

## See Also

- [Documentation Index](index.md)
- [Getting Started](getting-started.md)
- [Cells Reference](cells/index.md)
