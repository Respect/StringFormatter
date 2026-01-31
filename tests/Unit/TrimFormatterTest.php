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
use Respect\StringFormatter\InvalidFormatterException;
use Respect\StringFormatter\TrimFormatter;

#[CoversClass(TrimFormatter::class)]
final class TrimFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForWhitespace')]
    #[DataProvider('providerForSides')]
    #[DataProvider('providerForCustomMask')]
    #[DataProvider('providerForSpecialChars')]
    #[DataProvider('providerForUnicode')]
    #[DataProvider('providerForEmoji')]
    #[DataProvider('providerForMultiByte')]
    #[DataProvider('providerForEdgeCases')]
    public function itShouldTrimString(string $input, string $expected, string $side, string|null $mask = null): void
    {
        // @phpstan-ignore argument.type
        $formatter = new TrimFormatter($side, $mask);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldThrowExceptionForInvalidSide(): void
    {
        $this->expectException(InvalidFormatterException::class);
        $this->expectExceptionMessage('Invalid side "middle"');

        // @phpstan-ignore argument.type
        new TrimFormatter('middle');
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForWhitespace(): array
    {
        return [
            'whitespace both sides' => ['  hello  ', 'hello', 'both'],
            'tab both sides' => ["\thello\t", 'hello', 'both'],
            'newline both sides' => ["\nhello\n", 'hello', 'both'],
            'mixed whitespace both' => [" \t\n hello \t\n", 'hello', 'both'],
            'already trimmed both' => ['hello', 'hello', 'both'],
            'only spaces both' => ['     ', '', 'both'],
            'ideographic space both' => ["\u{3000}hello\u{3000}", 'hello', 'both'],
            'em space both' => ["\u{2003}hello\u{2003}", 'hello', 'both'],
            'no-break space both' => ["\u{00A0}hello\u{00A0}", 'hello', 'both'],
            'thin space both' => ["\u{2009}hello\u{2009}", 'hello', 'both'],
            'mixed unicode whitespace both' => ["\u{3000}\u{2003} hello \u{00A0}\u{2009}", 'hello', 'both'],
            'narrow no-break space both' => ["\u{202F}hello \u{202F}", 'hello', 'both'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForSides(): array
    {
        return [
            'spaces left' => ['  hello', 'hello', 'left'],
            'spaces right not trimmed left' => ['hello  ', 'hello  ', 'left'],
            'spaces left and right left' => ['  hello  ', 'hello  ', 'left'],
            'tabs left' => ["\thello\t", "hello\t", 'left'],
            'mixed whitespace left' => ["\t\n hello world", 'hello world', 'left'],
            'spaces right' => ['hello  ', 'hello', 'right'],
            'spaces left not trimmed right' => ['  hello', '  hello', 'right'],
            'spaces left and right right' => ['  hello  ', '  hello', 'right'],
            'tabs right' => ["\thello\t", "\thello", 'right'],
            'mixed whitespace right' => ["hello world \t", 'hello world', 'right'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForCustomMask(): array
    {
        return [
            'custom characters both' => ['---hello---', 'hello', 'both', '-'],
            'multiple custom chars both' => ['-._hello-._', 'hello', 'both', '_.-'],
            'dots both' => ['...hello...', 'hello', 'both', '.'],
            'underscores both' => ['___hello___', 'hello', 'both', '_'],
            'mixed custom both' => ['*-+hello+-*', 'hello', 'both', '+-*'],
            'dash left' => ['--hello--', 'hello--', 'left', '-'],
            'dash right' => ['--hello--', '--hello', 'right', '-'],
            'all characters to trim both' => ['  !!!  ', '!!!', 'both', ' '],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForSpecialChars(): array
    {
        return [
            'asterisk both' => ['**hello**', 'hello', 'both', '*'],
            'dollar sign both' => ['$$hello$$', 'hello', 'both', '$'],
            'caret both' => ['^^hello^^', 'hello', 'both', '^'],
            'pipe both' => ['||hello||', 'hello', 'both', '|'],
            'question mark both' => ['??hello??', 'hello', 'both', '?'],
            'multiple special both' => ['@#$hello$#@', 'hello', 'both', '@#$'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForUnicode(): array
    {
        return [
            'latin accented chars both' => ['éééhelloééé', 'hello', 'both', 'é'],
            'greek letters both' => ['αααhelloααα', 'hello', 'both', 'α'],
            'cyrillic letters both' => ['бббhelloббб', 'hello', 'both', 'б'],
            'chinese characters both' => ['中中hello中中', 'hello', 'both', '中'],
            'japanese hiragana both' => ['あああhelloあああ', 'hello', 'both', 'あ'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForEmoji(): array
    {
        return [
            'smiley faces both' => ['😊😊hello😊😊', 'hello', 'both', '😊'],
            'mixed emoji both' => ['👋👋hi👋👋', 'hi', 'both', '👋'],
            'hearts both' => ['❤️❤️love❤️❤️', 'love', 'both', '❤️'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3?: string}> */
    public static function providerForMultiByte(): array
    {
        return [
            'chinese with ideographic space both' => ['　你好　', '你好', 'both'],
            'japanese with ideographic space both' => ['　こんにちは　', 'こんにちは', 'both'],
            'korean with ideographic space both' => ['　안녕하세요　', '안녕하세요', 'both'],
            'fullwidth letters with custom mask both' => ['ａａａhelloａａａ', 'hello', 'both', 'ａ'],
            'mixed cjk and ascii both' => [' hello 你好 ', 'hello 你好', 'both'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3?: string}> */
    public static function providerForEdgeCases(): array
    {
        return [
            'empty string both' => ['', '', 'both', ' '],
            'string shorter than mask both' => ['a', '', 'both', 'abcdef'],
            'all characters trimmed both' => ['--', '', 'both', '-'],
            'only one side trimmed left' => ['--a', 'a', 'left', '-'],
            'only one side trimmed right' => ['a--', 'a', 'right', '-'],
            'no characters to trim both' => ['hello', 'hello', 'both', 'xyz'],
            'mask longer than string both' => ['hello', 'hello', 'both', 'abcdefgzij'],
            'empty mask both' => ['hello', 'hello', 'both', ''],
            'repeated characters both' => ['aaaaahelloaaaaa', 'hello', 'both', 'a'],
            'interleaved characters both' => ['ababhelloabab', 'hello', 'both', 'ab'],
        ];
    }
}
