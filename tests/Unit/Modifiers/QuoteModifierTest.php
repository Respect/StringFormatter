<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\Modifiers\QuoteModifier;
use Respect\StringFormatter\Test\Helper\TestingModifier;

#[CoversClass(QuoteModifier::class)]
final class QuoteModifierTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForPipeNotQuoteCases')]
    public function itShouldDelegateToNextModifierWhenPipeIsNotQuote(mixed $value, string|null $pipe): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new QuoteModifier($nextModifier);
        $expected = $nextModifier->modify($value, $pipe);

        $actual = $modifier->modify($value, $pipe);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: mixed, 1: string|null}> */
    public static function providerForPipeNotQuoteCases(): array
    {
        return [
            'non-string pipe value' => ['some string', 'notQuote'],
            'null pipe value' => ['some string', null],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonScalarWithQuotePipe')]
    public function itShouldDelegateToNextModifierWhenValueIsNotScalarAndPipeIsQuote(mixed $value): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new QuoteModifier($nextModifier);
        // Non-scalar values with 'quote' pipe should delegate with null pipe
        $expected = $nextModifier->modify($value, null);

        $actual = $modifier->modify($value, 'quote');

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: mixed}> */
    public static function providerForNonScalarWithQuotePipe(): array
    {
        return [
            'array value with quote pipe' => [['not', 'a', 'string']],
            'null value with quote pipe' => [null],
            'object value with quote pipe' => [(object) ['key' => 'value']],
        ];
    }

    #[Test]
    #[DataProvider('providerForScalarQuotingCases')]
    public function itShouldQuoteScalarValuesWithQuotePipe(
        mixed $value,
        string $expected,
    ): void {
        $modifier = new QuoteModifier(new TestingModifier());

        $actual = $modifier->modify($value, 'quote');

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: mixed, 1: string}> */
    public static function providerForScalarQuotingCases(): array
    {
        return [
            'integer value' => [42, '`42`'],
            'float value' => [3.14, '`3.14`'],
            'boolean true value' => [true, '`1`'],
            'boolean false value' => [false, '``'],
        ];
    }

    #[Test]
    #[DataProvider('providerForStringQuoting')]
    public function itShouldQuoteStringsWithVariousContent(string $input, string $expected, string $quote = '`'): void
    {
        $modifier = new QuoteModifier(new TestingModifier(), $quote);

        $actual = $modifier->modify($input, 'quote');

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForStringQuoting(): array
    {
        return [
            'simple string' => ['hello', '`hello`', '`'],
            'empty string' => ['', '``', '`'],
            'string with backticks' => ['he `hello` there', '`he \\`hello\\` there`', '`'],
            'string with backslashes' => ['path\\to\\file', '`path\\to\\file`', '`'],
            'string with special characters' => ['!@#$%^&*()', '`!@#$%^&*()`', '`'],
            'string with newlines' => ["line1\nline2", "`line1\nline2`", '`'],
            'unicode characters' => ['héllo 🌍', '`héllo 🌍`', '`'],
            'emoji string' => ['😀🎉', '`😀🎉`', '`'],
            'mixed language string' => ['Hello 世界', '`Hello 世界`', '`'],
            'html entities' => ['&lt;div&gt;', '`&lt;div&gt;`', '`'],
            'url string' => ['https://example.com', '`https://example.com`', '`'],
            'email string' => ['user@example.com', '`user@example.com`', '`'],
            'single quote string' => ["don't", "`don't`", '`'],
            'string with single quotes' => ["he's 'great'", "`he's 'great'`", '`'],
            'string with mixed quotes' => ['say "hello" and \'goodbye\'', "`say \"hello\" and 'goodbye'`", '`'],
            'test with single quotes' => ["can't stop", '"can\'t stop"', '"'],
            'test with double quotes' => ['hello "world"', '"hello \"world\""', '"'],
            'test with both quotes' => ['hello "world" and \'test\'', '"hello \"world\" and \'test\'"', '"'],
        ];
    }
}
