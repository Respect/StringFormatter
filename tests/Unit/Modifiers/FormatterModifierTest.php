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
use Respect\StringFormatter\DateFormatter;
use Respect\StringFormatter\FormatterBuilder;
use Respect\StringFormatter\MaskFormatter;
use Respect\StringFormatter\MetricFormatter;
use Respect\StringFormatter\Modifiers\FormatterModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\StringFormatter\NumberFormatter;
use Respect\StringFormatter\PatternFormatter;
use Respect\StringFormatter\Test\Helper\TestingModifier;

#[CoversClass(FormatterModifier::class)]
final class FormatterModifierTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForNonScalarValues')]
    public function itShouldDelegateWithNullPipeForNonScalarValues(
        mixed $value,
        string $pipe,
    ): void {
        $nextModifier = new TestingModifier('fallback');
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify($value, $pipe);

        self::assertSame('fallback', $result);
        self::assertNull($nextModifier->lastPipe);
    }

    #[Test]
    #[DataProvider('providerForInvalidFormatters')]
    public function itShouldDelegateWithOriginalPipeForInvalidFormatters(
        mixed $value,
        string $pipe,
    ): void {
        $nextModifier = new TestingModifier('fallback');
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify($value, $pipe);

        self::assertSame('fallback', $result);
        self::assertSame($pipe, $nextModifier->lastPipe);
    }

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
        $formatter = new DateFormatter();
        $value = '2024-01-15 10:30';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'date');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyDateFormatterWithCustomFormat(): void
    {
        $formatter = new DateFormatter('Y/m/d');
        $value = '2024-01-15';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'date:Y/m/d');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyNumberFormatterWithoutArguments(): void
    {
        $formatter = new NumberFormatter();
        $value = '1234.567';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'number');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyNumberFormatterWithDecimals(): void
    {
        $formatter = new NumberFormatter(2);
        $value = '1234.567';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'number:2');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyNumberFormatterWithAllArguments(): void
    {
        $formatter = FormatterBuilder::number(2, ',', '.');
        $value = '1234.567';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'number:2:,:.');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyMaskFormatterWithRange(): void
    {
        $formatter = new MaskFormatter('5-7');
        $value = '1234567890';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'mask:5-7');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyMaskFormatterWithRangeAndReplacement(): void
    {
        $formatter = new MaskFormatter('5-7', 'X');
        $value = '1234567890';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'mask:5-7:X');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyPatternFormatter(): void
    {
        $formatter = new PatternFormatter('(###) ###-####');
        $value = '1234567890';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'pattern:(###) ###-####');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldApplyMetricFormatter(): void
    {
        $formatter = new MetricFormatter('mm');
        $value = '1500';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'metric:mm');

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldConvertNonStringToStringBeforeFormatting(): void
    {
        $formatter = new MaskFormatter('1-3');
        $value = '123456';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify(123456, 'mask:1-3');

        self::assertSame($expected, $actual);
    }

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
    public function itShouldHandleInvalidDateInput(): void
    {
        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        // Invalid date format should cause formatter to return input unchanged
        $result = $modifier->modify('invalid date', 'date:invalid-format');

        self::assertSame('invalid date', $result);
    }

    #[Test]
    public function itShouldDelegateWhenFormatterCreationFails(): void
    {
        $nextModifier = new TestingModifier('fallback');
        $modifier = new FormatterModifier($nextModifier);

        $result = $modifier->modify('test', 'number:invalid-decimal');

        self::assertSame('fallback', $result);
    }

    #[Test]
    public function itShouldHandleEmptyArguments(): void
    {
        $formatter = new PatternFormatter('##########');
        $value = '1234567890';
        $expected = $formatter->format($value);

        $nextModifier = new StringifyModifier();
        $modifier = new FormatterModifier($nextModifier);

        $actual = $modifier->modify($value, 'pattern:##########');

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: mixed, 1: string}> */
    public static function providerForNonScalarValues(): array
    {
        return [
            'array value delegates with null pipe' => [['array'], 'date:Y/m/d'],
            'object value delegates with null pipe' => [(object) ['key' => 'value'], 'number:2'],
            'null value delegates with null pipe' => [null, 'mask:1-3'],
        ];
    }

    /** @return array<string, array{0: mixed, 1: string}> */
    public static function providerForInvalidFormatters(): array
    {
        return [
            'unknown formatter delegates with original pipe' => ['test value', 'unknown:formatter'],
            'invalid number formatter delegates with original pipe' => ['test', 'number:invalid-decimal'],
        ];
    }
}
