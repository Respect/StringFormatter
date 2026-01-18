<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\InvalidFormatterException;
use Respect\StringFormatter\PatternFormatter;

#[CoversClass(PatternFormatter::class)]
final class PatternFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForBasicFiltering')]
    public function itShouldApplyBasicFiltering(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForBasicFiltering(): array
    {
        return [
            'phone number formatting' => [
                '000-0000',
                '1234567890',
                '123-4567',
            ],
            'license plate format' => [
                'AAA-000',
                'ABC123',
                'ABC-123',
            ],
            'international postal code' => [
                'CC00CC',
                'AB123DE',
                'AB12DE',
            ],
            'US phone format' => [
                '(000) 000-0000',
                '1234567890',
                '(123) 456-7890',
            ],
            'SSN format' => [
                '000-00-0000',
                '123456789',
                '123-45-6789',
            ],
            'digits only' => [
                '000',
                'abc123def',
                '123',
            ],
            'uppercase only' => [
                'AAA',
                'abcDEFghi',
                'DEF',
            ],
            'lowercase only' => [
                'aaa',
                'ABCdefGHI',
                'def',
            ],
            'letters only' => [
                'CC',
                'abc123def',
                'ab',
            ],
            'word characters only' => [
                'WWWW',
                'abc123!@#',
                'abc1',
            ],

            'any character' => [
                '####',
                'abcdefgh',
                'abcd',
            ],
            'pattern longer than input' => [
                '###',
                'ab',
                'ab',
            ],
            'input longer than pattern' => [
                '####',
                'abcdefgh',
                'abcd',
            ],
            'no matching characters' => [
                'AAA',
                '123',
                '',
            ],
            'non-matching characters are skipped' => [
                'C0',
                'ABC123',
                'A1',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForCaseTransformations')]
    public function itShouldApplyCaseTransformations(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForCaseTransformations(): array
    {
        return [
            'lowercase next character' => [
                '\l###',
                'ABC',
                'aBC',
            ],
            'uppercase next character' => [
                '\u###',
                'abc',
                'Abc',
            ],
            'case transformation' => [
                '\l#\u#',
                'Ab',
                'aB',
            ],
            'invert next character' => [
                '\i###',
                'AbCd',
                'abC',
            ],
            'invert multiple characters with multiple \i' => [
                '\i#\i#\i###',
                'AbCDeF',
                'aBcDe',
            ],
            'uppercase until reset' => [
                '\U###',
                'abc',
                'ABC',
            ],
            'lowercase until reset' => [
                '\L####',
                'ABC1',
                'abc1',
            ],
            'case inversion until reset' => [
                '\I####',
                'AbCd',
                'aBcD',
            ],
            'uppercase with reset' => [
                '\U###\E##',
                'abcdef',
                'ABCde',
            ],
            'lowercase with reset' => [
                '\L###\E##',
                'ABCdef',
                'abcde',
            ],
            'invert with reset' => [
                '\I###\E##',
                'AbCdE',
                'aBcdE',
            ],
            'complex case transformations' => [
                '\l#\u#\l#\u#',
                'ABCD',
                'aBcD',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForEscapeSequences')]
    public function itShouldHandleEscapeSequences(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForEscapeSequences(): array
    {
        return [
            'literal hash character' => [
                '\#00',
                '#12',
                '#12',
            ],
            'literal zero character' => [
                '\0##',
                'ab',
                '0ab',
            ],
            'literal uppercase A' => [
                '\A##',
                'AB',
                'AAB',
            ],
            'literal at symbol' => [
                '\@##',
                'ab',
                '@ab',
            ],
            'literal with transformation' => [
                '\0###',
                'abc',
                '0abc',
            ],
            'mixed escapes and literals' => [
                '\#\A\0\@',
                '#A0@',
                '#A0@',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForComplexPatterns')]
    public function itShouldHandleComplexPatterns(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForComplexPatterns(): array
    {
        return [
            'deleting character' => [
                '##\d##',
                'ABCDE',
                'ABDE',
            ],
            'transformation reset' => [
                '\L##\E##',
                'ABCD',
                'abCD',
            ],
            'mixed filtering and transformations' => [
                '\U###-\L###',
                'abcDEF',
                'ABC-def',
            ],
            'complex phone format with case' => [
                '(\U###) \L###-####',
                'abcdefghij',
                '(ABC) def-ghij',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForUnicodeSupport')]
    public function itShouldSupportUnicode(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForUnicodeSupport(): array
    {
        return [
            'unicode uppercase transformation' => [
                '\U##',
                'ñáçé',
                'ÑÁ',
            ],
            'unicode letters only' => [
                'CC',
                'ñáç123',
                'ñá',
            ],
            'unicode word characters' => [
                'WW',
                'ñá1!',
                'ñá',
            ],
            'unicode mixed case transformation' => [
                '\l#\u#\l#\u#\l#',
                'ÁÉÍÓÚ',
                'áÉíÓú',
            ],
            'unicode with accented characters as literals' => [
                'ñ-###',
                'ábc',
                'ñ-ábc',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForEdgeCases')]
    public function itShouldHandleEdgeCases(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForEdgeCases(): array
    {
        return [
            'empty input' => [
                '###',
                '',
                '',
            ],
            'transformation without input' => [
                '\U###',
                '',
                '',
            ],
            'pattern longer than input uses all available' => [
                '###',
                'ab',
                'ab',
            ],
            'input longer than pattern truncates' => [
                '####',
                'abcdefgh',
                'abcd',
            ],
            'no matching characters found' => [
                'AAA',
                '123',
                '',
            ],
            'delete with no input' => [
                '\d###',
                '',
                '',
            ],
            'transformation state persists across pattern literals' => [
                '\U(###) ###',
                'abcdef',
                '(ABC) DEF',
            ],
        ];
    }

    #[Test]
    public function itShouldThrowExceptionForEmptyPattern(): void
    {
        $this->expectException(InvalidFormatterException::class);
        $this->expectExceptionMessage('Pattern cannot be empty');

        new PatternFormatter('');
    }

    #[Test]
    #[DataProvider('providerForRepetitionPatterns')]
    public function itShouldHandleRepetitionPatterns(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForRepetitionPatterns(): array
    {
        return [
            'exact range with min and max' => [
                'A{2,4}',
                'ABCDEFG',
                'ABCD',
            ],
            'one or more with plus' => [
                '0+',
                '123456789',
                '123456789',
            ],
            'zero or more with star' => [
                '#*',
                'abcdefgh',
                'abcdefgh',
            ],
            'minimum only (unbounded max)' => [
                '0{1,}',
                '123456789',
                '123456789',
            ],
            'maximum only (zero min)' => [
                '#{,5}',
                'abcdefghij',
                'abcde',
            ],
            'exact count shorthand' => [
                '0{3}',
                '123456',
                '123',
            ],
            'exact count with same min and max' => [
                '0{3,3}',
                '123456',
                '123',
            ],
            'repetition with literal prefix' => [
                'ID-0{3,}',
                'ID-12345',
                'ID-12345',
            ],
            'repetition with literal suffix' => [
                '0{3,}-END',
                '12345-END',
                '12345-END',
            ],
            'multiple repetitions in pattern' => [
                'A{2,3}-0{2,4}',
                'ABC-12345',
                'ABC-1234',
            ],
            'repetition with transformation' => [
                '\UC{3,5}',
                'abcdef',
                'ABCDE',
            ],
            'repetition skips non-matching' => [
                '0{3,}',
                'a1b2c3d4e5',
                '12345',
            ],
            'repetition with max less than available' => [
                '#{2,3}',
                'abcdefgh',
                'abc',
            ],
            'repetition with min greater than available' => [
                'A{5,10}',
                'ABC',
                'ABC',
            ],
            'zero min with no matching chars' => [
                'A{0,5}',
                '12345',
                '',
            ],
            'phone with repetition' => [
                '(0{3}) 0{3}-0{4}',
                '1234567890',
                '(123) 456-7890',
            ],
            'letters repetition with plus' => [
                'C+',
                'abc123def',
                'abcdef',
            ],
            'word chars repetition' => [
                'W{,10}',
                'abc123!@#xyz',
                'abc123xyz',
            ],
            'lowercase filter with repetition' => [
                'a{2,4}',
                'abcDEFghi',
                'abcg',
            ],
            'uppercase filter with repetition and transformation' => [
                '\LA+',
                'HELLO',
                'hello',
            ],
            'star with no matches returns empty' => [
                'A*-end',
                '123-end',
                '-end',
            ],
            'plus combined with literal' => [
                'ID-0+-X',
                'ID-12345-X',
                'ID-12345-X',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForDocumentedExamples')]
    public function itShouldHandleDocumentedExamples(string $pattern, string $input, string $expected): void
    {
        $formatter = new PatternFormatter($pattern);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForDocumentedExamples(): array
    {
        return [
            // Examples from the documentation table
            'phone number formatting from docs' => [
                '000-0000',
                '1234567',
                '123-4567',
            ],
            'license plate format from docs' => [
                'AAA-000',
                'ABC123',
                'ABC-123',
            ],
            'uppercase until reset from docs' => [
                '\U###',
                'abc',
                'ABC',
            ],
            'lowercase until reset from docs' => [
                '\L####',
                'ABC1',
                'abc1',
            ],
            'case transformation from docs' => [
                '\l#\u#',
                'Ab',
                'aB',
            ],
            'case inversion until reset from docs' => [
                '\I####',
                'AbCd',
                'aBcD',
            ],
            'international postal code from docs' => [
                'CC00WW',
                'AB123D',
                'AB123D',
            ],
            'US phone format from docs' => [
                '(000) 000-0000',
                '1234567890',
                '(123) 456-7890',
            ],
            'SSN format from docs' => [
                '000-00-0000',
                '123456789',
                '123-45-6789',
            ],
            'transformation reset from docs' => [
                '\L##\E##',
                'ABCD',
                'abCD',
            ],
            'deleting character from docs' => [
                '##\d##',
                'ABCDE',
                'ABDE',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForInvalidPatterns')]
    public function itShouldThrowExceptionForInvalidPatterns(string $pattern, string $expectedMessage): void
    {
        $this->expectException(InvalidFormatterException::class);
        $this->expectExceptionMessage($expectedMessage);

        new PatternFormatter($pattern);
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForInvalidPatterns(): array
    {
        return [
            'incomplete escape at end' => [
                '###\\',
                'Incomplete escape sequence at end of pattern',
            ],
            'orphaned plus at start' => [
                '+##',
                'Quantifier "+" must follow a filter pattern at position 0',
            ],
            'orphaned star at start' => [
                '*##',
                'Quantifier "*" must follow a filter pattern at position 0',
            ],
            'orphaned plus after literal' => [
                'X+##',
                'Quantifier "+" must follow a filter pattern at position 1',
            ],
            'orphaned star after literal' => [
                'X*##',
                'Quantifier "*" must follow a filter pattern at position 1',
            ],
            'empty braces' => [
                '#{}',
                'Invalid or malformed quantifier at position 1',
            ],
            'both empty in braces' => [
                '#{,}',
                'Invalid or malformed quantifier at position 1',
            ],
            'unclosed brace' => [
                '#{5',
                'Invalid or malformed quantifier at position 1',
            ],
            'invalid brace content' => [
                '#{abc}',
                'Invalid or malformed quantifier at position 1',
            ],
        ];
    }
}
