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
use Respect\StringFormatter\UppercaseFormatter;

#[CoversClass(UppercaseFormatter::class)]
final class UppercaseFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForValidFormattedString')]
    public function testShouldFormatString(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testShouldHandleEmptyString(): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format('');

        self::assertSame('', $actual);
    }

    #[Test]
    #[DataProvider('providerForUnicodeString')]
    public function testShouldHandleUnicodeCharacters(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForLatinAccents')]
    public function testShouldHandleLatinCharactersWithAccents(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNonLatinScripts')]
    public function testShouldHandleNonLatinScripts(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForEmojiAndSpecialChars')]
    public function testShouldHandleEmojiAndSpecialCharacters(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForCombiningDiacritics')]
    public function testShouldHandleCombiningDiacritics(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForRightToLeft')]
    public function testShouldHandleRightToLeftText(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForMultiByte')]
    public function testShouldHandleMultiByteCharacters(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNumbersAndSpecial')]
    public function testShouldHandleNumbersAndSpecialChars(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForMixed')]
    public function testShouldHandleMixedContent(string $input, string $expected): void
    {
        $formatter = new UppercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForValidFormattedString(): array
    {
        return [
            'empty string' => ['', ''],
            'single lowercase letter' => ['a', 'A'],
            'all lowercase' => ['hello', 'HELLO'],
            'already uppercase' => ['HELLO', 'HELLO'],
            'mixed case' => ['Hello World', 'HELLO WORLD'],
            'with punctuation' => ['hello, world!', 'HELLO, WORLD!'],
            'with numbers' => ['hello123', 'HELLO123'],
            'single word' => ['test', 'TEST'],
            'multiple words' => ['test string case', 'TEST STRING CASE'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForUnicodeString(): array
    {
        return [
            'german umlauts' => ['über', 'ÜBER'],
            'french accents' => ['café', 'CAFÉ'],
            'spanish tilde' => ['niño', 'NIÑO'],
            'portuguese' => ['coração', 'CORAÇÃO'],
            'icelandic' => ['þingvellir', 'ÞINGVELLIR'],
            'scandinavian' => ['ørsted', 'ØRSTED'],
            'polish' => ['łęski', 'ŁĘSKI'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForLatinAccents(): array
    {
        return [
            'c-cedilla' => ['café français', 'CAFÉ FRANÇAIS'],
            'umlauts' => ['äöü', 'ÄÖÜ'],
            'tilde' => ['ãñõ', 'ÃÑÕ'],
            'circumflex' => ['êîôû', 'ÊÎÔÛ'],
            'acute' => ['áéíóú', 'ÁÉÍÓÚ'],
            'grave' => ['àèìòù', 'ÀÈÌÒÙ'],
            'mixed accents' => ['résumé déjà vu', 'RÉSUMÉ DÉJÀ VU'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForNonLatinScripts(): array
    {
        return [
            'greek lowercase' => ['γεια σας', 'ΓΕΙΑ ΣΑΣ'],
            'cyrillic lowercase' => ['привет мир', 'ПРИВЕТ МИР'],
            'arabic' => ['مرحبا', 'مرحبا'],
            'hebrew' => ['שלום', 'שלום'],
            'thai' => ['สวัสดี', 'สวัสดี'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForEmojiAndSpecialChars(): array
    {
        return [
            'smiley face' => ['hello 😊', 'HELLO 😊'],
            'multiple emoji' => ['hi 👋 bye 👋', 'HI 👋 BYE 👋'],
            'hearts' => ['❤️ love ❤️', '❤️ LOVE ❤️'],
            'special symbols' => ['© ™ ®', '© ™ ®'],
            'math symbols' => ['∑ π ∫', '∑ Π ∫'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForCombiningDiacritics(): array
    {
        return [
            'e with combining acute' => ["e\u{0301}", "E\u{0301}"],
            'a with combining grave' => ["a\u{0300}", "A\u{0300}"],
            'multiple diacritics' => ["e\u{0301}\u{0301}", "E\u{0301}\u{0301}"],
            'word with combining marks' => ["cafe\u{0301}", "CAFE\u{0301}"],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForRightToLeft(): array
    {
        return [
            'arabic word' => ['مرحبا', 'مرحبا'],
            'hebrew word' => ['שלום', 'שלום'],
            'mixed direction' => ['hello مرحبا', 'HELLO مرحبا'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForMultiByte(): array
    {
        return [
            'chinese' => ['你好世界', '你好世界'],
            'japanese hiragana' => ['こんにちは', 'こんにちは'],
            'japanese katakana' => ['ハローワールド', 'ハローワールド'],
            'korean hangul' => ['안녕하세요', '안녕하세요'],
            'cjk characters' => ['简体字繁體字漢字', '简体字繁體字漢字'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForNumbersAndSpecial(): array
    {
        return [
            'digits only' => ['1234567890', '1234567890'],
            'mixed alphanumeric' => ['abc123def', 'ABC123DEF'],
            'special chars only' => ['!@#$%^&*()', '!@#$%^&*()'],
            'whitespace' => ['   ', '   '],
            'tabs and newlines' => ["hello\tworld\n", "HELLO\tWORLD\n"],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForMixed(): array
    {
        return [
            'unicode with numbers' => ['café123', 'CAFÉ123'],
            'emoji with text' => ['Hello World 😊', 'HELLO WORLD 😊'],
            'cjk with latin' => ['Hello你好', 'HELLO你好'],
            'mixed scripts' => ['Hello 世界 Мир', 'HELLO 世界 МИР'],
            'complex string' => ['CAFé 123 😊 你好', 'CAFÉ 123 😊 你好'],
        ];
    }
}
