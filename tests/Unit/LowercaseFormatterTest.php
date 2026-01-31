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
use Respect\StringFormatter\LowercaseFormatter;

#[CoversClass(LowercaseFormatter::class)]
final class LowercaseFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForValidFormattedString')]
    public function testShouldFormatString(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testShouldHandleEmptyString(): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format('');

        self::assertSame('', $actual);
    }

    #[Test]
    #[DataProvider('providerForUnicodeString')]
    public function testShouldHandleUnicodeCharacters(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForLatinAccents')]
    public function testShouldHandleLatinCharactersWithAccents(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNonLatinScripts')]
    public function testShouldHandleNonLatinScripts(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForEmojiAndSpecialChars')]
    public function testShouldHandleEmojiAndSpecialCharacters(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForTurkish')]
    public function testShouldHandleTurkishCharacters(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForCombiningDiacritics')]
    public function testShouldHandleCombiningDiacritics(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForRightToLeft')]
    public function testShouldHandleRightToLeftText(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForMultiByte')]
    public function testShouldHandleMultiByteCharacters(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNumbersAndSpecial')]
    public function testShouldHandleNumbersAndSpecialChars(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForMixed')]
    public function testShouldHandleMixedContent(string $input, string $expected): void
    {
        $formatter = new LowercaseFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForValidFormattedString(): array
    {
        return [
            'empty string' => ['', ''],
            'single uppercase letter' => ['A', 'a'],
            'all uppercase' => ['HELLO', 'hello'],
            'already lowercase' => ['hello', 'hello'],
            'mixed case' => ['Hello World', 'hello world'],
            'with punctuation' => ['Hello, World!', 'hello, world!'],
            'with numbers' => ['Hello123', 'hello123'],
            'single word' => ['TEST', 'test'],
            'multiple words' => ['Test String Case', 'test string case'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForUnicodeString(): array
    {
        return [
            'german umlauts' => ['ÜBER', 'über'],
            'french accents' => ['CAFÉ', 'café'],
            'spanish tilde' => ['NIÑO', 'niño'],
            'portuguese' => ['CORAÇÃO', 'coração'],
            'icelandic' => ['ÞINGVELLIR', 'þingvellir'],
            'scandinavian' => ['ØRSTED', 'ørsted'],
            'polish' => ['ŁĘSKI', 'łęski'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForLatinAccents(): array
    {
        return [
            'c-cedilla' => ['CAFÉ FRANÇAIS', 'café français'],
            'umlauts' => ['ÄÖÜ', 'äöü'],
            'tilde' => ['ÃÑÕ', 'ãñõ'],
            'circumflex' => ['ÊÎÔÛ', 'êîôû'],
            'acute' => ['ÁÉÍÓÚ', 'áéíóú'],
            'grave' => ['ÀÈÌÒÙ', 'àèìòù'],
            'mixed accents' => ['RÉSUMÉ DÉJÀ VU', 'résumé déjà vu'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForNonLatinScripts(): array
    {
        return [
            'greek uppercase' => ['ΓΕΙΑ ΣΑΣ', 'γεια σας'],
            'cyrillic uppercase' => ['ПРИВЕТ МИР', 'привет мир'],
            'arabic' => ['مرحبا', 'مرحبا'],
            'hebrew' => ['שלום', 'שלום'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForEmojiAndSpecialChars(): array
    {
        return [
            'smiley face' => ['HELLO 😊', 'hello 😊'],
            'multiple emoji' => ['HI 👋 BYE 👋', 'hi 👋 bye 👋'],
            'hearts' => ['❤️ LOVE ❤️', '❤️ love ❤️'],
            'special symbols' => ['© ™ ®', '© ™ ®'],
            'math symbols' => ['∑ π ∫', '∑ π ∫'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForTurkish(): array
    {
        return [
            'turkish i' => ['İ', 'i̇'],
            'turkish I' => ['I', 'i'],
            'turkish mixed' => ['İSTANBUL', 'i̇stanbul'],
            'capital i with dot' => ['İi', 'i̇i'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForCombiningDiacritics(): array
    {
        return [
            'E with combining acute' => ["E\u{0301}", "e\u{0301}"],
            'A with combining grave' => ["A\u{0300}", "a\u{0300}"],
            'combined character' => ['É', 'é'],
            'word with combining marks' => ["CAFE\u{0301}", "cafe\u{0301}"],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForRightToLeft(): array
    {
        return [
            'arabic word' => ['مرحبا', 'مرحبا'],
            'hebrew word' => ['שלום', 'שלום'],
            'mixed direction' => ['HELLO مرحبا', 'hello مرحبا'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForMultiByte(): array
    {
        return [
            'e-acute' => ['É', 'é'],
            'u-umlaut' => ['Ü', 'ü'],
            'greek sigma' => ['Σ', 'σ'],
            'cyrillic de' => ['Д', 'д'],
            'polish l-stroke' => ['Ł', 'ł'],
            'full accented word' => ['RÉSUMÉ', 'résumé'],
            'mixed multibyte and ascii' => ['ÜBER COOL', 'über cool'],
            'multibyte with cjk' => ['CAFÉ你好', 'café你好'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForNumbersAndSpecial(): array
    {
        return [
            'digits only' => ['1234567890', '1234567890'],
            'mixed alphanumeric' => ['ABC123DEF', 'abc123def'],
            'special chars only' => ['!@#$%^&*()', '!@#$%^&*()'],
            'whitespace' => ['   ', '   '],
            'tabs and newlines' => ["HELLO\tWORLD\n", "hello\tworld\n"],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForMixed(): array
    {
        return [
            'unicode with numbers' => ['CAFÉ123', 'café123'],
            'emoji with text' => ['HELLO WORLD 😊', 'hello world 😊'],
            'cjk with latin' => ['HELLO你好', 'hello你好'],
            'mixed scripts' => ['HELLO 世界 МИР', 'hello 世界 мир'],
            'complex string' => ['CAFÉ 123 😊 你好', 'café 123 😊 你好'],
        ];
    }
}
