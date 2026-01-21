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
use Respect\StringFormatter\MaskFormatter;

use function sprintf;

#[CoversClass(MaskFormatter::class)]
final class MaskFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForMaskWithRanges')]
    public function itShouldMaskWithRanges(string $range, string $replacement, string $input, string $expected): void
    {
        $formatter = new MaskFormatter($range, $replacement);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForMaskWithRanges(): array
    {
        return [
            'numeric ranges' => [
                '1-3,8-12',
                '*',
                '1234123412341234',
                '***4123*****1234',
            ],
            'character delimiter at' => [
                '1-@',
                '*',
                'username@domain.com',
                '********@domain.com',
            ],
            'numeric positions' => [
                '1-5',
                '*',
                'password123',
                '*****ord123',
            ],
            'complex character ranges' => [
                'A-D,5-8',
                '#',
                'ABCD1234567890EFGH',
                '###D####567890EFGH',
            ],
            'escaped numeral' => [
                '3-\5',
                'X',
                '1234567890',
                '12XX567890',
            ],

            'invalid positions' => [
                '10-15',
                '*',
                'short',
                'short',
            ],
            'multiple ranges' => [
                '1-3,6-8,10',
                '#',
                'abcdefghij',
                '###de###i#',
            ],
            'escaped special character' => [
                '1-\+',
                '*',
                'email+tag@domain.com',
                '*****+tag@domain.com',
            ],
            'dash as delimiter - escaped' => [
                'A-\-',
                '#',
                'ABCD-1234567890EFGH',
                '####-1234567890EFGH',
            ],
            'comma as delimiter - escaped' => [
                'B-\,',
                '*',
                'ABC,DEF,GHI',
                '**C,DEF,GHI',
            ],

            'empty input' => [
                '1-3',
                '*',
                '',
                '',
            ],
            'beyond string length' => [
                '1-10',
                '*',
                'abc',
                '***',
            ],
            'to end from start' => [
                '1-',
                '*',
                '12345678',
                '********',
            ],
            'to end from position 3' => [
                '3-',
                '*',
                '12345678',
                '12******',
            ],
            'to end from position 5' => [
                '5-',
                '#',
                'abcdefgh',
                'abcd####',
            ],
            'to end from last position' => [
                '9-',
                '*',
                '123456789',
                '12345678*',
            ],
            'last two characters' => [
                '-2',
                '#',
                '123456',
                '1234##',
            ],
            'last three characters' => [
                '-3',
                'X',
                'abcdefgh',
                'abcdeXXX',
            ],
            'last all characters' => [
                '-5',
                '*',
                'hello',
                '*****',
            ],
            'multiple delimiters in range' => [
                '1-$',
                '*',
                'a#b@c$d',
                '*****$d',
            ],
            'mixed character and numeric ranges' => [
                '1-c,2-5',
                '#',
                'abc123def456',
                '#####3def456',
            ],
            'backslash escaped character' => [
                '1-\\\\',
                '*',
                'path\to\file',
                '****\to\file',
            ],
            'unicode with accents full range' => [
                '1-',
                '*',
                'oftalmoscópico',
                '**************',
            ],
            'unicode with accents specific range' => [
                '3-8',
                '#',
                'oftalmoscópico',
                'of######cópico',
            ],
            'unicode with multiple accents' => [
                '2-6,9',
                '*',
                'áéíóúãõçüñ',
                'á*****õç*ñ',
            ],
            'unicode with accent as delimiter' => [
                'á-ú',
                '#',
                'áááóóóíííúúú',
                '#########úúú',
            ],
            'unicode mixed ascii and accents' => [
                '1-5,8-10',
                '*',
                'testéando123',
                '*****an***23',
            ],
            'unicode with escaped accent character' => [
                'm-\ó',
                '#',
                'camiónés',
                '####ónés',
            ],
            'unicode last N characters with accents' => [
                '-4',
                '*',
                'españolñç',
                'españ****',
            ],
            'unicode range with accent character not found' => [
                'x-z',
                '#',
                'simples',
                'simples',
            ],
            'multiple ranges with dynamic to end 1-2,7-' => [
                '1-2,7-',
                '*',
                '1234567890',
                '**3456****',
            ],
            'non-existent character delimiter' => [
                '@',
                '*',
                'abc',
                'abc',
            ],
            'multiple ranges with dynamic to end 3-5,-8' => [
                '3-5,-8',
                '#',
                'abcdefghijklmnopqrs',
                'ab###fghijk########',
            ],
            'mixed numeric and dynamic ranges' => [
                '2-3,6-',
                'X',
                '123456789',
                '1XX45XXXX',
            ],
            // Japanese tests
            'japanese hiragana full mask' => [
                '1-',
                '*',
                'こんにちは世界',
                '*******',
            ],
            'japanese hiragana specific range' => [
                '3-6',
                '#',
                'こんにちは世界',
                'こん####界',
            ],
            'japanese mixed kanji hiragana' => [
                '1-2',
                '*',
                '東京の天気',
                '**の天気',
            ],
            'japanese last characters' => [
                '-3',
                '#',
                'お元気ですか',
                'お元気###',
            ],
            // Chinese tests
            'chinese simplified full mask' => [
                '1-',
                '*',
                '你好世界',
                '****',
            ],
            'chinese simplified middle range' => [
                '2-5',
                '#',
                '中国人民共和国',
                '中####和国',
            ],
            'chinese traditional' => [
                '3-4',
                '*',
                '您好世界',
                '您好**',
            ],
            'chinese last characters' => [
                '-2',
                '#',
                '中文测试',
                '中文##',
            ],
            // Thai tests
            'thai full mask' => [
                '1-',
                '*',
                'สวัสดีชาวโลก',
                '************',
            ],
            'thai specific range' => [
                '2-5',
                '#',
                'ขอบคุณครับ',
                'ข####ณครับ',
            ],
            'thai last characters' => [
                '-4',
                '*',
                'ยินดีครับ',
                'ยินดี****',
            ],
            // Hebrew tests
            'hebrew full mask' => [
                '1-',
                '*',
                'שלום עולם',
                '*********',
            ],
            'hebrew middle range' => [
                '2-4',
                '#',
                'บוקר טוב',
                'บ### טוב',
            ],
            'hebrew last characters' => [
                '-3',
                '*',
                'מה שלומך',
                'מה של***',
            ],
            // Mixed language tests
            'japanese and ascii' => [
                '1-@',
                '*',
                'user田中@example.com',
                '******@example.com',
            ],
            'chinese with english' => [
                '6-7',
                '#',
                'hello中国world',
                'hello##world',
            ],
            // RTL scripts complex scenarios
            'hebrew with numbers middle' => [
                '5-8',
                '*',
                'מספר12345',
                'מספר****5',
            ],
            'thai with special symbols' => [
                '1-@',
                '#',
                '@email@ทดสอบ.com',
                '#email@ทดสอบ.com',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForInvalidMaskRanges')]
    public function itShouldThrowExceptionForInvalidMaskRanges(string $range): void
    {
        $this->expectException(InvalidFormatterException::class);
        $this->expectExceptionMessage(sprintf('"%s" is not a valid mask range', $range));

        new MaskFormatter($range);
    }

    /** @return array<string, array{0: string}> */
    public static function providerForInvalidMaskRanges(): array
    {
        return [
            'zero start' => ['0-3'],
            'escaped dash with spaces' => ['\- - @'],
            'inverted numeric range' => ['3-1'],
            'unescaped dash' => ['-'],
            'negative last N' => ['-0'],
            'invalid last pattern' => ['-abc'],
            'empty range' => [''],

            'single backslash' => ['\\'],
            'range with spaces' => ['1 - @'],
            'only comma' => [','],

            'range with same start and end' => ['1-1'],
        ];
    }
}
