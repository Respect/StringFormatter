# Contributing to StringFormatter

Thank you for considering contributing to StringFormatter! This document provides guidelines and information for contributors.

## Getting Started

### Prerequisites

- PHP 8.5 or higher
- Composer
- Git

### Setting Up the Development Environment

1. **Fork the repository**

```bash
# Fork the repository on GitHub, then clone your fork
git clone https://github.com/yourusername/StringFormatter.git
cd StringFormatter
```

2. **Install dependencies**

```bash
composer install
```

3. **Run tests**

```bash
composer test
```

## How to Contribute

### Adding New Formatters

StringFormatter is designed to be extensible. All formatters must implement the `Respect\StringFormatter\Formatter` interface.

**Steps to add a new formatter:**

1. **Create the formatter class**

```php
<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

final readonly class YourFormatter implements Formatter
{
    public function format(string $input): string
    {
        // Your formatting implementation
        return $formattedInput;
    }
}
```

2. **Create tests**

```php
<?php
// tests/Unit/YourFormatterTest.php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\YourFormatter;

final class YourFormatterTest extends TestCase
{
    // Test your formatter implementation
}
```

3. **Add documentation**
   - Create `docs/YourFormatter.md` following the template used by MaskFormatter
   - Include usage examples, API reference, and any special considerations

4. **Update README.md**
   - Add your formatter to the "Formatters" table

### Testing

- **Unit tests**: Located in `tests/Unit/`
- **Run all tests**: `composer test`

All contributions must include tests that pass.

### Code Style

This project follows the PSR-12 coding standard via the Respect Coding Standard. Run the following command before submitting:

```bash
composer lint  # Check coding style
composer format # Fix coding style automatically
```

### Static Analysis

This project uses PHPStan for static analysis. Run:

```bash
composer analyze  # Run static analysis
```

### Submitting Changes

1. **Create a feature branch**

```bash
git checkout -b feature/your-feature-name
```

2. **Make your changes**
   - Add your formatter implementation
   - Include comprehensive tests
   - Update documentation
   - Follow PSR-12 coding standards

3. **Test your changes**

```bash
composer test    # Run tests
composer lint     # Check code style
composer format   # Fix coding style automatically
composer analyze  # Run static analysis
```

4. **Commit your changes**

```bash
git add .
git commit -m "Add YourFormatter for [purpose]"
```

5. **Push and create a pull request**

```bash
git push origin feature/your-feature-name
```

Then create a pull request on GitHub with:

- Clear description of the changes
- Link to any relevant issues
- Explain the use case and benefits

## Development Commands

| Command            | Description                      |
| ------------------ | -------------------------------- |
| `composer install` | Install dependencies             |
| `composer test`    | Run all tests                    |
| `composer lint`    | Check PSR-12 compliance          |
| `composer format`  | Fix coding style automatically   |
| `composer analyze` | Run static analysis with PHPStan |

## Guidelines

### Do

- Write clear, well-documented code
- Include comprehensive tests
- Follow PSR-12 coding standards
- Use type declarations everywhere
- Write commit messages that explain the "why" not just the "what"

### Don't

- Submit changes without tests
- Break backward compatibility unless necessary
- Use PHP without strict typing
- Commit sensitive information
- Mix multiple concerns in a single PR

## Reporting Issues

When reporting issues, please include:

- PHP version
- Library version
- Complete error messages
- Minimal reproducible example
- Expected vs actual behavior

## Questions

If you have questions about contributing, feel free to:

- Open an issue with the "question" label
- Start a discussion in the repository discussions

Thank you for contributing to StringFormatter! 🎉
