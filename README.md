<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# Respect\StringFormatter

[![Build Status](https://img.shields.io/github/actions/workflow/status/Respect/StringFormatter/continuous-integration.yml?branch=main&style=flat-square)](https://github.com/Respect/StringFormatter/actions/workflows/continuous-integration.yml)
[![Code Coverage](https://img.shields.io/codecov/c/github/Respect/StringFormatter?style=flat-square)](https://codecov.io/gh/Respect/StringFormatter)
[![Latest Stable Version](https://img.shields.io/packagist/v/respect/string-formatter.svg?style=flat-square)](https://packagist.org/packages/respect/string-formatter)
[![Total Downloads](https://img.shields.io/packagist/dt/respect/string-formatter.svg?style=flat-square)](https://packagist.org/packages/respect/string-formatter)
[![License](https://img.shields.io/packagist/l/respect/string-formatter.svg?style=flat-square)](https://packagist.org/packages/respect/string-formatter)

A powerful and flexible PHP library for formatting and transforming strings.

## Installation

```bash
composer require respect/string-formatter
```

## Formatters

| Formatter                                            | Description                                         |
| ---------------------------------------------------- | --------------------------------------------------- |
| [MaskFormatter](docs/MaskFormatter.md)               | Range-based string masking with Unicode support     |
| [PatternFormatter](docs/PatternFormatter.md)         | Pattern-based string filtering with placeholders    |
| [PlaceholderFormatter](docs/PlaceholderFormatter.md) | Template interpolation with placeholder replacement |

## Contributing

Please see our [Contributing Guide](CONTRIBUTING.md) for information on how to contribute to this project.

## License

This project is licensed under the ISC License - see the LICENSE file for details.
