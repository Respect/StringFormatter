<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\InvalidFormatterException;
use Respect\StringFormatter\TrimFormatter;

#[CoversClass(TrimFormatter::class)]
final class TrimFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForValidFormattedString')]
    public function testShouldTrimString(string $input, string $expected, string $side = 'both', string $mask = " \t\n\r\0\x0B"): void
    {
        $formatter = new TrimFormatter($mask, $side);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForLeftTrim')]
    public function testShouldTrimLeft(string $input, string $expected, string $mask = " \t\n\r\0\x0B"): void
    {
        $formatter = new TrimFormatter($mask, 'left');

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForRightTrim')]
    public function testShouldTrimRight(string $input, string $expected, string $mask = " \t\n\r\0\x0B"): void
    {
        $formatter = new TrimFormatter($mask, 'right');

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForBothTrim')]
    public function testShouldTrimBoth(string $input, string $expected, string $mask = " \t\n\r\0\x0B"): void
    {
        $formatter = new TrimFormatter($mask, 'both');

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testShouldHandleEmptyString(): void
    {
        $formatter = new TrimFormatter();

        $actual = $formatter->format('');

        self::assertSame('', $actual);
    }

    #[Test]
    public function testShouldThrowExceptionForInvalidSide(): void
    {
        $this->expectException(InvalidFormatterException::class);
        $this->expectExceptionMessage('Invalid side "middle"');

        new TrimFormatter(' ', 'middle');
    }

    #[Test]
    #[DataProvider('providerForUnicode')]
    public function testShouldHandleUnicodeCharacters(string $input, string $expected, string $mask): void
    {
        $formatter = new TrimFormatter($mask);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForEmoji')]
    public function testShouldHandleEmoji(string $input, string $expected, string $mask): void
    {
        $formatter = new TrimFormatter($mask);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForCustomMask')]
    public function testShouldHandleCustomMask(string $input, string $expected, string $mask): void
    {
        $formatter = new TrimFormatter($mask);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForSpecialChars')]
    public function testShouldHandleSpecialCharactersInMask(string $input, string $expected, string $mask): void
    {
        $formatter = new TrimFormatter($mask);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForMultiByte')]
    public function testShouldHandleMultiByteCharacters(string $input, string $expected, string $mask): void
    {
        $formatter = new TrimFormatter($mask);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForEdgeCases')]
    public function testShouldHandleEdgeCases(string $input, string $expected, string $side, string $mask): void
    {
        $formatter = new TrimFormatter($mask, $side);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2?: string, 3?: string}> */
    public static function providerForValidFormattedString(): array
    {
        return [
            'whitespace both sides' => ['  hello  ', 'hello'],
            'tab both sides' => ["\thello\t", 'hello'],
            'newline both sides' => ["\nhello\n", 'hello'],
            'mixed whitespace' => [" \t\n hello \t\n", 'hello'],
            'already trimmed' => ['hello', 'hello'],
            'only spaces' => ['     ', ''],
            'no characters in mask' => ['hello', 'hello', 'both', 'xyz'],
            'all characters to trim' => ['  !!!  ', '!!!', 'both', ' '],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2?: string}> */
    public static function providerForLeftTrim(): array
    {
        return [
            'spaces left' => ['  hello', 'hello'],
            'spaces right not trimmed' => ['hello  ', 'hello  '],
            'spaces left and right' => ['  hello  ', 'hello  '],
            'tabs left' => ["\thello\t", "hello\t"],
            'mixed whitespace left' => ["\t\n hello world", 'hello world'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2?: string}> */
    public static function providerForRightTrim(): array
    {
        return [
            'spaces right' => ['hello  ', 'hello'],
            'spaces left not trimmed' => ['  hello', '  hello'],
            'spaces left and right' => ['  hello  ', '  hello'],
            'tabs right' => ["\thello\t", "\thello"],
            'mixed whitespace right' => ['hello world \t', 'hello world \t'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2?: string}> */
    public static function providerForBothTrim(): array
    {
        return [
            'spaces both' => ['  hello  ', 'hello'],
            'tabs both' => ["\thello\t", 'hello'],
            'newlines both' => ["\nhello\n", 'hello'],
            'mixed whitespace' => [" \t\n hello \t\n ", 'hello'],
            'single space' => [' hello ', 'hello'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForUnicode(): array
    {
        return [
            'cjk spaces' => ['　hello　', 'hello', '　'],
            'unicode spaces' => ["\u{2003}hello\u{2003}", 'hello', "\u{2003}"],
            'latin accented chars' => ['éééhelloééé', 'hello', 'é'],
            'greek letters' => ['αααhelloααα', 'hello', 'α'],
            'cyrillic letters' => ['бббhelloббб', 'hello', 'б'],
            'arabic letters' => ['مرحبا', 'ا', 'مرحب'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForEmoji(): array
    {
        return [
            'smiley faces' => ['😊😊hello😊😊', 'hello', '😊'],
            'mixed emoji' => ['👋👋hi👋👋', 'hi', '👋'],
            'hearts' => ['❤️❤️love❤️❤️', 'love', '❤️'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForCustomMask(): array
    {
        return [
            'custom characters' => ['---hello---', 'hello', '-'],
            'multiple custom chars' => ['-._hello-._', 'hello', '_.-'],
            'dots' => ['...hello...', 'hello', '.'],
            'underscores' => ['___hello___', 'hello', '_'],
            'mixed custom' => ['*-+hello+-*', '*-+hello+-*', '+-*'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForSpecialChars(): array
    {
        return [
            'dash' => ['--hello--', 'hello', '-'],
            'asterisk' => ['**hello**', 'hello', '*'],
            'dot' => ['..hello..', 'hello', '.'],
            'dollar sign' => ['$$hello$$', 'hello', '$'],
            'caret' => ['^^hello^^', 'hello', '^'],
            'pipe' => ['||hello||', 'hello', '|'],
            'question mark' => ['??hello??', 'hello', '?'],
            'multiple special' => ['@#$hello$#@', 'hello', '@#$'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForMultiByte(): array
    {
        return [
            'chinese spaces' => ['　你好　', '你好', '　'],
            'japanese whitespace' => ['　こんにちは　', 'こんにちは', '　'],
            'korean whitespace' => ['　안녕하세요　', '안녕하세요', '　'],
            'cjk fullwidth' => ['ａｂｃ', 'ａｂｃ', '　'],
            'mixed cjk and ascii' => [' hello 你好 ', 'hello 你好', ' '],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForEdgeCases(): array
    {
        return [
            'empty string' => ['', '', 'both', ' '],
            'string shorter than mask' => ['a', '', 'both', 'abcdef'],
            'all characters trimmed' => ['--', '', 'both', '-'],
            'only one side trimmed left' => ['--a', 'a', 'left', '-'],
            'only one side trimmed right' => ['a--', 'a', 'right', '-'],
            'no characters to trim' => ['hello', 'hello', 'both', 'xyz'],
            'mask longer than string' => ['hello', 'hello', 'both', 'abcdefgzij'],
            'empty mask' => ['hello', 'hello', 'both', ''],
            'repeated characters' => ['aaaaahelloaaaaa', 'hello', 'both', 'a'],
            'interleaved characters' => ['ababhelloabab', 'hello', 'both', 'ab'],
        ];
    }
}
