# Agent Instructions

This file contains instructions for AI agents working on this repository.

## Development Commands

| Command            | Description                      |
| ------------------ | -------------------------------- |
| `composer install` | Install dependencies             |
| `composer test`    | Run all tests                    |
| `composer lint`    | Check PSR-12 compliance          |
| `composer format`  | Fix coding style automatically   |
| `composer analyze` | Run static analysis with PHPStan |

## Testing & Quality Standards

- **Unit tests**: Located in `tests/Unit/`
- **Testing guidelines**: See `docs/contributing/testing-guidelines.md` for patterns
- **Data-driven testing**: Use comprehensive providers with descriptive keys
- **Arrange-Act-Assert**: Structure tests with clear setup, action, and assertion sections
- **No mocks**: Create custom test implementations instead of PHPUnit mocks
- **All contributions must include tests that pass**
- **Code style**: Follow PSR-12 coding standard
- **Static analysis**: Use PHPStan, fix any issues before submitting

## Formatter Development

When creating new formatters:

1. **Class template**: `docs/contributing/templates/php/src/TemplateFormatter.php`
2. **Test template**: `docs/contributing/templates/php/tests/TemplateFormatterTest.php`
3. **Documentation template**: `docs/contributing/templates/formatter-documentation-template.md`

All formatters must implement the `Respect\StringFormatter\Formatter` interface.

## Modifier Development

When creating new modifiers:

1. **Follow Chain of Responsibility pattern**: Check pipe value and delegate to next modifier
2. **Use template structure**: Similar to `src/Modifier/QuoteModifier.php`
3. **Test with TestingModifier**: Located in `tests/Helper/TestingModifier.php`
4. **Handle type checking**: Always check input types before processing
5. **Return string values**: Modifiers must return strings
6. **Use Stringifier Quoter**: For string operations, inject `\Respect\Stringifier\Quoter` with CodeQuoter as default

All modifiers must implement the `Respect\StringFormatter\Modifier` interface.

## Testing Guidelines

1. **Avoid PHPUnit mocks**: Create custom test implementations instead of using createMock()
2. **Use custom test quoter**: Follow pattern in `tests/Helper/TestingQuoter.php`
3. **Test contracts not implementations**: Verify interactions without depending on specific behavior
4. **Make test properties public**: When using anonymous classes to access test state
5. **Verify method calls**: Track whether methods were called and with what parameters

## Commit Guidelines

Follow the detailed rules in `docs/contributing/commit-guidelines.md`:

- Title: 5-116 characters, imperative mood, starts with capital
- Body: Explain WHY, max 116 characters per line
- Footer: Use trailers for references and AI attribution

## Repository Structure

- `/src/` - Formatter implementations
- `/tests/Unit/` - Unit tests
- `/docs/` - Documentation including formatter docs
- `/docs/contributing/` - Contribution guidelines and templates

## Before Submitting Changes

1. Run composer commands: `test`, `lint`, `analyze`
2. Ensure documentation follows templates
3. Check commit message follows guidelines
4. Verify all tests pass

Remember: Reference external docs rather than duplicating information in this file.
