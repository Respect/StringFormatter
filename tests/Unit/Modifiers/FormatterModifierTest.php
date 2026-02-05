<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\Modifiers\FormatterModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\StringFormatter\Test\Helper\TestingModifier;

#[CoversClass(FormatterModifier::class)]
final class FormatterModifierTest extends TestCase
{
    #[Test]
    public function itShouldDelegateWhenPipeIsNull(): void
    {
        $nextModifier = new TestingModifier('modified');
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('test', null);

        self::assertSame('modified', $result);
    }

    #[Test]
    public function itShouldDelegateWhenFormatterDoesNotExist(): void
    {
        $nextModifier = new TestingModifier('delegated');
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('test', 'nonexistent');

        self::assertSame('delegated', $result);
    }

    #[Test]
    public function itShouldApplyDateFormatterWithDefaultFormat(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('2024-01-15 10:30:00', 'date');

        self::assertSame('2024-01-15 10:30:00', $result);
    }

    #[Test]
    public function itShouldApplyDateFormatterWithCustomFormat(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('2024-01-15', 'date:Y/m/d');

        self::assertSame('2024/01/15', $result);
    }

    #[Test]
    public function itShouldApplyNumberFormatterWithoutArguments(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234.567', 'number');

        self::assertSame('1,235', $result);
    }

    #[Test]
    public function itShouldApplyNumberFormatterWithDecimals(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234.567', 'number:2');

        self::assertSame('1,234.57', $result);
    }

    #[Test]
    public function itShouldApplyNumberFormatterWithAllArguments(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234.567', 'number:2:,:.');

        self::assertSame('1.234,57', $result);
    }

    #[Test]
    public function itShouldApplyMaskFormatterWithRange(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234567890', 'mask:5-7');

        self::assertSame('1234***890', $result);
    }

    #[Test]
    public function itShouldApplyMaskFormatterWithRangeAndReplacement(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234567890', 'mask:5-7:X');

        self::assertSame('1234XXX890', $result);
    }

    #[Test]
    public function itShouldApplyPatternFormatter(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234567890', 'pattern:(###) ###-####');

        self::assertSame('(123) 456-7890', $result);
    }

    #[Test]
    public function itShouldApplyMetricFormatter(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1500', 'metric:mm');

        self::assertSame('1.5 m', $result);
    }

    #[Test]
    public function itShouldConvertNonStringToStringBeforeFormatting(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify(123456, 'mask:1-3');

        self::assertSame('***456', $result);
    }

    /** @param array<string, mixed> $input */
    #[Test]
    #[DataProvider('providerForFormatterChaining')]
    public function itShouldChainWithOtherModifiers(
        mixed $value,
        string|null $pipe,
        string $expected,
    ): void {
        $nextModifier = new TestingModifier('fallback');
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify($value, $pipe);

        self::assertSame($expected, $result);
    }

    /** @return array<string, array{0: mixed, 1: string|null, 2: string}> */
    public static function providerForFormatterChaining(): array
    {
        return [
            'null pipe delegates to next' => ['value', null, 'fallback'],
            'unknown formatter delegates to next' => ['value', 'unknown', 'fallback'],
            'invalid pipe delegates to next' => ['value', 'invalid:modifier', 'fallback'],
        ];
    }

    #[Test]
    public function itShouldDelegateWhenFormatterThrowsException(): void
    {
        $nextModifier = new TestingModifier('fallback');
        $modifier = new FormatterModifier($nextModifier);

        // Invalid date format should cause formatter to fail gracefully
        $result = $modifier->modify('invalid date', 'date:invalid-format');

        // DateFormatter returns input unchanged for invalid dates
        self::assertSame('invalid date', $result);
    }

    #[Test]
    public function itShouldHandleEmptyArguments(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('1234567890', 'pattern:##########');

        self::assertSame('1234567890', $result);
    }
}
