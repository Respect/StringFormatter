<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# Development Commands

| Command            | Description                      |
| ------------------ | -------------------------------- |
| `composer install` | Install dependencies             |
| `composer test`    | Run all tests                    |
| `composer lint`    | Check PSR-12 compliance          |
| `composer format`  | Fix coding style automatically   |
| `composer analyze` | Run static analysis with PHPStan |

## Testing

- **Unit tests**: Located in `tests/Unit/`
- **Run all tests**: `composer test`

All contributions must include tests that pass.

## Code Style

This project follows the PSR-12 coding standard via the Respect Coding Standard. Run the following command before submitting:

```bash
composer lint  # Check coding style
composer format # Fix coding style automatically
```

## Static Analysis

This project uses PHPStan for static analysis. Run:

```bash
composer analyze  # Run static analysis
```
