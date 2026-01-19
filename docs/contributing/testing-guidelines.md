# Testing Guidelines

This document outlines the testing standards and practices for contributions to StringFormatter.

## Core Testing Principles

1. Use Data-Driven Testing: Create comprehensive data providers with descriptive string keys
2. Test behaviors, not implementations: Focus on input/output results through public interfaces
3. Use real instances: Avoid PHPUnit mocks - create custom test implementations when needed
4. Black box testing: Verify interactions through results, not internal state

## Test Structure Standards

### Class Organization

- All test classes must be `final`
- Use the `Respect\StringFormatter\Test\Unit` namespace
- Add `#[CoversClass(ClassName::class)]` attribute for code coverage
- Extend `PHPUnit\Framework\TestCase`

### Method Organization

- Use `#[Test]` attribute for test methods
- Use `#[DataProvider('methodName')]` for parameterized tests
- Follow naming pattern: `itShould[Behavior]When[Condition]` or `itShould[ExpectedBehavior]`

## Data Provider Patterns

```php
/ @return array<string, array{0: InputType, 1: TemplateType, 2: ExpectedType}> */
public static function providerForFeatureName(): array
{
    return [
        'descriptive test case name' => [
            $input,
            $template,
            $expected,
        ],
        'another test case' => [
            $input2,
            $template2,
            $expected2,
        ],
    ];
}
```

### Provider Requirements

- Use descriptive string keys for each test case
- Document array structure with PHPDoc array shapes
- Include edge cases, error conditions, and real-world scenarios
- Test Unicode support and internationalization when applicable

## Test Structure: Arrange-Act-Assert

Follow the Arrange-Act-Assert pattern for clear test organization:

```php
#[Test]
public function itShouldFormatTemplateCorrectly(): void
{
    // Arrange: Set up test data and objects
    $parameters = ['name' => 'John'];
    $template = 'Hello {{name}}!';
    $expected = 'Hello John!';

    // Act: Execute the method being tested
    $formatter = new FormatterClass($parameters);
    $actual = $formatter->format($template);

    // Assert: Verify the result
    self::assertSame($expected, $actual);
}
```

### Pattern Benefits

- **Arrange**: Clearly separates test setup
- **Act**: Highlights the specific behavior being tested
- **Assert**: Makes verification explicit and easy to read

## Assertion Patterns

### Primary Assertions

```php
// Following Arrange-Act-Assert pattern:
// 1. Arrange: Create formatter and setup (done above)
// 2. Act: Call format method (done above)
// 3. Assert: Verify result
self::assertSame($expected, $actual);
```

### Exception Testing

```php
#[Test]
public function itShouldThrowExceptionForInvalidInput(): void
{
    $this->expectException(InvalidFormatterException::class);
    $this->expectExceptionMessage('Specific error message');

    new FormatterClass($invalidInput);
}
```

## Coverage Requirements

### Test Coverage Areas

- Happy Path: Primary functionality with valid inputs
- Edge Cases: Empty inputs, boundary conditions, malformed data
- Error Conditions: Invalid inputs, exception scenarios
- Type Support: All PHP types when the class under test handler mixed types directly
- Unicode: International characters, emoji, mixed languages
- Real-world Usage: Email templates, log messages, URLs, etc.

## Code Quality Standards

### Test Organization

- Each test method validates one specific behavior
- Group related tests logically by feature
- Keep test setup minimal and inline
- Use descriptive test case names in data providers

### Documentation Standards

- Document test methods with PHPDoc when behavior isn't obvious
- Use type annotations for complex parameters in data providers
- Include real-world examples in test cases

## Dependencies and Mocks

### Preferred Approach

- Create real instances of any objects needed for testing
- Use custom test implementations instead of PHPUnit mocks
- Test through public APIs like `format()` and `formatWith()`

### When Custom Implementations Are Needed

```php
class DumpStringifier implements Stringifier
{
    public function stringify(mixed $value): string
    {
        return var_export($value, true);
    }
}
```

## Static Analysis Compatibility

- Write tests that pass PHPStan static analysis
- Use proper type annotations in PHPDoc
- Ensure test code follows PSR-12 coding standard

## Examples from Codebase

See these test files for reference patterns:

- `tests/Unit/PlaceholderFormatterTest.php` - Data-driven testing with comprehensive providers
- `tests/Unit/PatternFormatterTest.php` - Exception testing and edge case coverage
- `tests/Unit/JavascriptFormatterTest.php` - Unicode and internationalization testing

Remember: Tests should be clear, maintainable, and focused on verifying desired behaviors rather than implementation details.
