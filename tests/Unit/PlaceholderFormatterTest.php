<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Test\Helper\TestingModifier;
use stdClass;
use Stringable;

use function sprintf;

#[CoversClass(PlaceholderFormatter::class)]
final class PlaceholderFormatterTest extends TestCase
{
    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForBasicInterpolation')]
    public function itShouldInterpolateBasicTemplates(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForBasicInterpolation(): array
    {
        return [
            'single placeholder' => [
                ['name' => 'John'],
                'Hello {{name}}!',
                'Hello John!',
            ],
            'multiple placeholders' => [
                ['name' => 'John', 'age' => 30],
                'Hello {{name}}, you are {{age}} years old.',
                'Hello John, you are 30 years old.',
            ],
            'repeated placeholder' => [
                ['name' => 'Alice'],
                '{{name}} loves {{name}}!',
                'Alice loves Alice!',
            ],
            'placeholder at start' => [
                ['greeting' => 'Hello'],
                '{{greeting}} World',
                'Hello World',
            ],
            'placeholder at end' => [
                ['name' => 'Bob'],
                'Hello {{name}}',
                'Hello Bob',
            ],
            'only placeholder' => [
                ['value' => 'test'],
                '{{value}}',
                'test',
            ],
            'multiple placeholders in sequence' => [
                ['first' => 'A', 'second' => 'B', 'third' => 'C'],
                '{{first}}{{second}}{{third}}',
                'ABC',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForMissingParameters')]
    public function itShouldKeepPlaceholderForMissingParameters(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForMissingParameters(): array
    {
        return [
            'missing parameter' => [
                ['name' => 'John'],
                'Hello {{name}}, you are {{age}} years old.',
                'Hello John, you are {{age}} years old.',
            ],
            'all missing parameters' => [
                [],
                'Hello {{name}}, you are {{age}} years old.',
                'Hello {{name}}, you are {{age}} years old.',
            ],
            'mixed existing and missing' => [
                ['first' => 'A', 'third' => 'C'],
                '{{first}}-{{second}}-{{third}}',
                'A-{{second}}-C',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForNullValues')]
    public function itShouldConvertNullValuesToString(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForNullValues(): array
    {
        return [
            'null value' => [
                ['name' => null],
                'Hello {{name}}!',
                'Hello `null`!',
            ],
            'mixed null and non-null' => [
                ['first' => 'A', 'second' => null, 'third' => 'C'],
                '{{first}}-{{second}}-{{third}}',
                'A-`null`-C',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForTypeConversions')]
    public function itShouldConvertTypesToStrings(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForTypeConversions(): array
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return 'StringableObject';
            }
        };

        return [
            'integer value' => [
                ['count' => 42],
                'The count is {{count}}',
                'The count is 42',
            ],
            'float value' => [
                ['price' => 19.99],
                'Price: ${{price}}',
                'Price: $19.99',
            ],
            'boolean true' => [
                ['active' => true],
                'Active: {{active}}',
                'Active: `true`',
            ],
            'boolean false' => [
                ['active' => false],
                'Active: {{active}}',
                'Active: `false`',
            ],
            'empty string' => [
                ['value' => ''],
                'Value: [{{value}}]',
                'Value: []',
            ],
            'zero integer' => [
                ['value' => 0],
                'Value: {{value}}',
                'Value: 0',
            ],
            'zero float' => [
                ['value' => 0.0],
                'Value: {{value}}',
                'Value: 0.0',
            ],
            'stringable object' => [
                ['obj' => $stringable],
                'Object: {{obj}}',
                'Object: `Stringable@anonymous { __toString() => "StringableObject" }`',
            ],
        ];
    }

    #[Test]
    public function itShouldConvertArrayToString(): void
    {
        $formatter = new PlaceholderFormatter(['items' => ['a', 'b', 'c']]);
        $actual = $formatter->format('Items: {{items}}');

        self::assertStringContainsString('a', $actual);
        self::assertStringContainsString('b', $actual);
        self::assertStringContainsString('c', $actual);
    }

    #[Test]
    public function itShouldConvertObjectToString(): void
    {
        $obj = new stdClass();
        $obj->name = 'test';

        $formatter = new PlaceholderFormatter(['obj' => $obj]);
        $actual = $formatter->format('Object: {{obj}}');

        self::assertStringContainsString('stdClass', $actual);
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForEdgeCases')]
    public function itShouldHandleEdgeCases(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForEdgeCases(): array
    {
        return [
            'empty template' => [
                ['name' => 'John'],
                '',
                '',
            ],
            'no placeholders' => [
                ['name' => 'John'],
                'Hello World!',
                'Hello World!',
            ],
            'empty parameters with template' => [
                [],
                'No {{placeholders}} here',
                'No {{placeholders}} here',
            ],
            'placeholder with numbers in name' => [
                ['value1' => 'A', 'value2' => 'B'],
                '{{value1}} and {{value2}}',
                'A and B',
            ],
            'placeholder with underscore' => [
                ['first_name' => 'John', 'last_name' => 'Doe'],
                '{{first_name}} {{last_name}}',
                'John Doe',
            ],
            'template with only text' => [
                ['unused' => 'value'],
                'Just plain text',
                'Just plain text',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForMalformedPlaceholders')]
    public function itShouldKeepMalformedPlaceholdersAsLiterals(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForMalformedPlaceholders(): array
    {
        return [
            'single brace' => [
                ['name' => 'John'],
                'Hello {name}!',
                'Hello {name}!',
            ],
            'triple braces' => [
                ['name' => 'John'],
                'Hello {{{name}}}!',
                'Hello {John}!',
            ],
            'opening braces only' => [
                ['name' => 'John'],
                'Hello {{name!',
                'Hello {{name!',
            ],
            'closing braces only' => [
                ['name' => 'John'],
                'Hello name}}!',
                'Hello name}}!',
            ],
            'placeholder with spaces' => [
                ['name' => 'John'],
                'Hello {{ name }}!',
                'Hello {{ name }}!',
            ],
            'placeholder with special chars' => [
                ['name' => 'John'],
                'Hello {{name-value}}!',
                'Hello {{name-value}}!',
            ],
            'empty placeholder' => [
                ['name' => 'John'],
                'Hello {{}}!',
                'Hello {{}}!',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForUnicodeSupport')]
    public function itShouldSupportUnicode(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForUnicodeSupport(): array
    {
        return [
            'unicode in template' => [
                ['name' => 'José'],
                'Hola {{name}}!',
                'Hola José!',
            ],
            'unicode in value' => [
                ['greeting' => 'Привет'],
                '{{greeting}} World',
                'Привет World',
            ],
            'unicode in placeholder name' => [
                ['nombre' => 'Juan'],
                'Hola {{nombre}}!',
                'Hola Juan!',
            ],
            'emoji in template' => [
                ['emoji' => '🎉'],
                'Celebration {{emoji}}',
                'Celebration 🎉',
            ],
            'emoji in value' => [
                ['icon' => '🔥'],
                'Hot {{icon}}',
                'Hot 🔥',
            ],
            'mixed unicode characters' => [
                ['text' => 'Здравствуй мир'],
                'Message: {{text}}',
                'Message: Здравствуй мир',
            ],
            'chinese characters' => [
                ['greeting' => '你好'],
                '{{greeting}}，世界',
                '你好，世界',
            ],
            'arabic characters' => [
                ['text' => 'مرحبا'],
                '{{text}} world',
                'مرحبا world',
            ],
        ];
    }

    #[Test]
    public function itShouldAcceptCustomModifier(): void
    {
        $value = new stdClass();
        $pipe = 'pipe';
        $placeholder = 'placeholder';

        $modifier = new TestingModifier();

        $expected = 'The value is ' . $modifier->modify($value, $pipe);

        $formatter = new PlaceholderFormatter([$placeholder => $value], $modifier);
        $actual = $formatter->format(sprintf('The value is {{%s|%s}}', $placeholder, $pipe));

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldAlwaysCallModifierWhenParameterExists(): void
    {
        $modifier = new TestingModifier('modified');
        $placeholder = 'name';

        $formatter = new PlaceholderFormatter([$placeholder => 'John'], $modifier);
        $actual = $formatter->format(sprintf('Hello {{%s}}!', $placeholder));

        self::assertSame('Hello modified!', $actual);
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForRealWorldUseCases')]
    public function itShouldHandleRealWorldUseCases(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForRealWorldUseCases(): array
    {
        return [
            'email template' => [
                ['name' => 'Alice', 'product' => 'Widget', 'price' => 29.99],
                'Dear {{name}}, your order for {{product}} (${{price}}) has been confirmed.',
                'Dear Alice, your order for Widget ($29.99) has been confirmed.',
            ],
            'log message' => [
                ['user' => 'admin', 'action' => 'login', 'ip' => '192.168.1.1'],
                '[{{user}}] {{action}} from {{ip}}',
                '[admin] login from 192.168.1.1',
            ],
            'notification message' => [
                ['count' => 5, 'type' => 'messages'],
                'You have {{count}} new {{type}}.',
                'You have 5 new messages.',
            ],
            'URL generation' => [
                ['domain' => 'example.com', 'path' => 'api/users', 'id' => 123],
                'https://{{domain}}/{{path}}/{{id}}',
                'https://example.com/api/users/123',
            ],
            'SQL-like template' => [
                ['table' => 'users', 'field' => 'email', 'value' => 'test@example.com'],
                'SELECT * FROM {{table}} WHERE {{field}} = {{value}}',
                'SELECT * FROM users WHERE email = test@example.com',
            ],
        ];
    }

    #[Test]
    public function itShouldAcceptEmptyParametersArray(): void
    {
        $formatter = new PlaceholderFormatter([]);
        $actual = $formatter->format('Hello World');

        self::assertSame('Hello World', $actual);
    }

    /**
     * @param array<string, mixed> $constructorParameters
     * @param array<string, mixed> $additionalParameters
     */
    #[Test]
    #[DataProvider('providerForFormatWith')]
    public function itShouldFormatWithAdditionalParameters(
        array $constructorParameters,
        array $additionalParameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($constructorParameters);
        $actual = $formatter->formatUsing($template, $additionalParameters);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: array<string, mixed>, 2: string, 3: string}> */
    public static function providerForFormatWith(): array
    {
        return [
            'additional parameters only' => [
                [],
                ['name' => 'John'],
                'Hello {{name}}!',
                'Hello John!',
            ],
            'constructor parameters only' => [
                ['name' => 'John'],
                [],
                'Hello {{name}}!',
                'Hello John!',
            ],
            'merged parameters without overlap' => [
                ['name' => 'John'],
                ['age' => 30],
                'Hello {{name}}, you are {{age}} years old.',
                'Hello John, you are 30 years old.',
            ],
            'constructor parameters take precedence' => [
                ['name' => 'John'],
                ['name' => 'Jane'],
                'Hello {{name}}!',
                'Hello John!',
            ],
            'mixed precedence with multiple keys' => [
                ['name' => 'John', 'city' => 'New York'],
                ['name' => 'Jane', 'age' => 25, 'city' => 'Boston'],
                '{{name}} from {{city}} is {{age}} years old.',
                'John from New York is 25 years old.',
            ],
            'additional parameters fill missing values' => [
                ['greeting' => 'Hello'],
                ['name' => 'World', 'punctuation' => '!'],
                '{{greeting}} {{name}}{{punctuation}}',
                'Hello World!',
            ],
            'empty additional parameters' => [
                ['name' => 'John'],
                [],
                'Hello {{name}}!',
                'Hello John!',
            ],
            'both empty parameters' => [
                [],
                [],
                'Hello {{name}}!',
                'Hello {{name}}!',
            ],
            'additional null value does not override' => [
                ['name' => 'John'],
                ['name' => null],
                'Hello {{name}}!',
                'Hello John!',
            ],
            'type conversion in additional parameters' => [
                [],
                ['count' => 42, 'active' => true],
                'Count: {{count}}, Active: {{active}}',
                'Count: 42, Active: `true`',
            ],
        ];
    }

    #[Test]
    public function itShouldNotModifyOriginalFormatterBehavior(): void
    {
        $formatter = new PlaceholderFormatter(['name' => 'John']);

        // Call formatUsing first
        $withResult = $formatter->formatUsing('Hello {{name}} and {{other}}!', ['other' => 'World']);

        // Then call format - should still work with original parameters only
        $formatResult = $formatter->format('Hello {{name}} and {{other}}!');

        self::assertSame('Hello John and World!', $withResult);
        self::assertSame('Hello John and {{other}}!', $formatResult);
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForComplexScenarios')]
    public function itShouldHandleComplexScenarios(array $parameters, string $template, string $expected): void
    {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForComplexScenarios(): array
    {
        return [
            'many placeholders' => [
                ['a' => '1', 'b' => '2', 'c' => '3', 'd' => '4', 'e' => '5'],
                '{{a}}-{{b}}-{{c}}-{{d}}-{{e}}',
                '1-2-3-4-5',
            ],
            'long template' => [
                ['name' => 'Bob'],
                'Hello {{name}}, welcome to our service. We are glad to have you, {{name}}!',
                'Hello Bob, welcome to our service. We are glad to have you, Bob!',
            ],
            'nested-looking placeholders' => [
                ['outer' => 'value'],
                '{{outer}}',
                'value',
            ],
            'adjacent placeholders without separator' => [
                ['first' => 'Hello', 'second' => 'World'],
                '{{first}}{{second}}',
                'HelloWorld',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForEscapedColons')]
    public function itShouldHandleEscapedColonsInFormatterArguments(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForEscapedColons(): array
    {
        return [
            'pattern with escaped colon' => [
                ['time' => '1234'],
                '{{time|pattern:##\:##}}',
                '12:34',
            ],
            'pattern with multiple escaped colons' => [
                ['time' => '123456'],
                '{{time|pattern:##\:##\:##}}',
                '12:34:56',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForEscapedPipes')]
    public function itShouldHandleEscapedPipesInFormatterArguments(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForEscapedPipes(): array
    {
        return [
            'pattern with escaped pipe' => [
                ['value' => '123456'],
                '{{value|pattern:###\|###}}',
                '123|456',
            ],
            'pattern with multiple escaped pipes' => [
                ['value' => '12345678'],
                '{{value|pattern:##\|##\|##\|##}}',
                '12|34|56|78',
            ],
        ];
    }
}
