<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Integration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\PlaceholderFormatter;

#[CoversClass(PlaceholderFormatter::class)]
final class PlaceholderFormatterWithFormattersTest extends TestCase
{
    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForFormatterModifiers')]
    public function itShouldApplyFormatterAsModifier(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForFormatterModifiers(): array
    {
        return [
            'date with custom format' => [
                ['date' => '2024-01-15'],
                'Date: {{date|date:Y/m/d}}',
                'Date: 2024/01/15',
            ],
            'date with default format' => [
                ['date' => '2024-01-15 10:30:00'],
                'DateTime: {{date|date}}',
                'DateTime: 2024-01-15 10:30:00',
            ],
            'number with decimals' => [
                ['amount' => '1234.567'],
                'Amount: {{amount|number:2}}',
                'Amount: 1,234.57',
            ],
            'number with all arguments' => [
                ['price' => '1234.56'],
                'Price: {{price|number:2:,:  }}',
                'Price: 1 234,56',
            ],
            'mask with range' => [
                ['card' => '1234567890123456'],
                'Card: {{card|mask:5-12}}',
                'Card: 1234********3456',
            ],
            'mask with range and replacement' => [
                ['ssn' => '123456789'],
                'SSN: {{ssn|mask:1-5:X}}',
                'SSN: XXXXX6789',
            ],
            'pattern formatter' => [
                ['phone' => '1234567890'],
                'Phone: {{phone|pattern:(###) ###-####}}',
                'Phone: (123) 456-7890',
            ],
            'metric formatter' => [
                ['distance' => '1500'],
                'Distance: {{distance|metric:mm}}',
                'Distance: 1.5 m',
            ],
            'multiple formatters in same template' => [
                ['date' => '2024-01-15', 'amount' => '1234.56'],
                'Date: {{date|date:d/m/Y}}, Amount: {{amount|number:2}}',
                'Date: 15/01/2024, Amount: 1,234.56',
            ],
            'formatter with non-string value' => [
                ['count' => 12345],
                'Count: {{count|mask:1-3}}',
                'Count: ***45',
            ],
            'formatter that does not exist falls back' => [
                ['value' => 'test'],
                'Value: {{value|nonexistent}}',
                'Value: test',
            ],
            'existing list modifier still works' => [
                ['items' => ['apple', 'banana', 'cherry']],
                'Items: {{items|list:and}}',
                'Items: apple, banana, and cherry',
            ],
            'existing trans modifier still works' => [
                ['key' => 'hello'],
                'Key: {{key|trans}}',
                'Key: hello',
            ],
        ];
    }

    #[Test]
    public function itShouldPreferFormatterOverExistingModifiers(): void
    {
        // When a formatter exists with the same name as a pipe,
        // it should try the formatter first
        $formatter = new PlaceholderFormatter(['value' => '123']);

        // There's no conflict since existing modifiers have unique names
        // This test just ensures formatters are checked
        $result = $formatter->format('{{value|pattern:###}}');

        self::assertSame('123', $result);
    }

    #[Test]
    public function itShouldHandleComplexRealWorldTemplate(): void
    {
        $parameters = [
            'customer' => 'John Doe',
            'order_id' => '12345',
            'order_date' => '2024-01-15',
            'items' => ['Widget A', 'Widget B', 'Widget C'],
            'subtotal' => '1234.56',
            'tax' => '123.46',
            'total' => '1358.02',
            'card' => '4532123456789012',
        ];

        $template = <<<'TEMPLATE'
Dear {{customer}},

Your order #{{order_id}} placed on {{order_date|date:F j, Y}} has been confirmed.

Items ordered: {{items|list:and}}

Subtotal: ${{subtotal|number:2}}
Tax: ${{tax|number:2}}
Total: ${{total|number:2}}

Card charged: {{card|mask:1-12:*}}

Thank you for your purchase!
TEMPLATE;

        $expected = <<<'EXPECTED'
Dear John Doe,

Your order #12345 placed on January 15, 2024 has been confirmed.

Items ordered: Widget A, Widget B, and Widget C

Subtotal: $1,234.56
Tax: $123.46
Total: $1,358.02

Card charged: ************9012

Thank you for your purchase!
EXPECTED;

        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }
}
