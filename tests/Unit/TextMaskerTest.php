<?php

declare(strict_types=1);

namespace Respect\Masker\Test\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Masker\TextMasker;

#[CoversClass(TextMasker::class)]
final class TextMaskerTest extends TestCase
{
    private TextMasker $masker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->masker = new TextMasker();
    }

    #[Test]
    #[DataProvider('providerForMaskWithRanges')]
    public function itShouldMaskWithRanges(string $input, string $maskChar, string $maskRanges, string $expected): void
    {
        $actual = $this->masker->mask($input, $maskRanges, $maskChar);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public static function providerForMaskWithRanges(): array
    {
        return [
            'numeric ranges' => [
                '1234123412341234',
                '*',
                '1-3,8-12',
                '***4123*****1234',
            ],
            'character delimiter at' => [
                'username@domain.com',
                '*',
                '1-@',
                '********@domain.com',
            ],
            'numeric positions' => [
                'password123',
                '*',
                '1-5',
                '*****ord123',
            ],
            'complex character ranges' => [
                'ABCD1234567890EFGH',
                '#',
                'A-D,5-8',
                '###D####567890EFGH',
            ],
            'escaped numeral' => [
                '1234567890',
                'X',
                '3-\5',
                '12XX567890',
            ],

            'invalid positions' => [
                'short',
                '*',
                '10-15',
                'short',
            ],
            'multiple ranges' => [
                'abcdefghij',
                '#',
                '1-3,6-8,10',
                '###de###i#',
            ],
            'escaped special character' => [
                'email+tag@domain.com',
                '*',
                '1-\+',
                '*****+tag@domain.com',
            ],
            'dash as delimiter - escaped' => [
                'ABCD-1234567890EFGH',
                '#',
                'A-\-',
                '####-1234567890EFGH',
            ],
            'comma as delimiter - escaped' => [
                'ABC,DEF,GHI',
                '*',
                'B-\,',
                '**C,DEF,GHI',
            ],

            'empty input' => [
                '',
                '*',
                '1-3',
                '',
            ],
            'beyond string length' => [
                'abc',
                '*',
                '1-10',
                '***',
            ],
            'to end from start' => [
                '12345678',
                '*',
                '1-',
                '********',
            ],
            'to end from position 3' => [
                '12345678',
                '*',
                '3-',
                '12******',
            ],
            'to end from position 5' => [
                'abcdefgh',
                '#',
                '5-',
                'abcd####',
            ],
            'to end from last position' => [
                '123456789',
                '*',
                '9-',
                '12345678*',
            ],
            'last two characters' => [
                '123456',
                '#',
                '-2',
                '1234##',
            ],
            'last three characters' => [
                'abcdefgh',
                'X',
                '-3',
                'abcdeXXX',
            ],
            'last all characters' => [
                'hello',
                '*',
                '-5',
                '*****',
            ],
            'multiple delimiters in range' => [
                'a#b@c$d',
                '*',
                '1-$',
                '*****$d',
            ],
            'mixed character and numeric ranges' => [
                'abc123def456',
                '#',
                '1-c,2-5',
                '#####3def456',
            ],
            'backslash escaped character' => [
                'path\to\file',
                '*',
                '1-\\\\',
                '****\to\file',
            ],
            'unicode with accents full range' => [
                'oftalmoscópico',
                '*',
                '1-',
                '**************',
            ],
            'unicode with accents specific range' => [
                'oftalmoscópico',
                '#',
                '3-8',
                'of######cópico',
            ],
            'unicode with multiple accents' => [
                'áéíóúãõçüñ',
                '*',
                '2-6,9',
                'á*****õç*ñ',
            ],
            'unicode with accent as delimiter' => [
                'áááóóóíííúúú',
                '#',
                'á-ú',
                '#########úúú',
            ],
            'unicode mixed ascii and accents' => [
                'testéando123',
                '*',
                '1-5,8-10',
                '*****an***23',
            ],
            'unicode with escaped accent character' => [
                'camiónés',
                '#',
                'm-\ó',
                '####ónés',
            ],
            'unicode last N characters with accents' => [
                'españolñç',
                '*',
                '-4',
                'españ****',
            ],
            'unicode range with accent character not found' => [
                'simples',
                '#',
                'x-z',
                'simples',
            ],
            'multiple ranges with dynamic to end 1-2,7-' => [
                '1234567890',
                '*',
                '1-2,7-',
                '**3456****',
            ],
            'non-existent character delimiter' => [
                'abc',
                '*',
                '@',
                'abc',
            ],
            'multiple ranges with dynamic to end 3-5,-8' => [
                'abcdefghijklmnopqrs',
                '#',
                '3-5,-8',
                'ab###fghijk########',
            ],
            'mixed numeric and dynamic ranges' => [
                '123456789',
                'X',
                '2-3,6-',
                '1XX45XXXX',
            ],
            // Japanese tests
            'japanese hiragana full mask' => [
                'こんにちは世界',
                '*',
                '1-',
                '*******',
            ],
            'japanese hiragana specific range' => [
                'こんにちは世界',
                '#',
                '3-6',
                'こん####界',
            ],
            'japanese mixed kanji hiragana' => [
                '東京の天気',
                '*',
                '1-2',
                '**の天気',
            ],
            'japanese last characters' => [
                'お元気ですか',
                '#',
                '-3',
                'お元気###',
            ],
            // Chinese tests
            'chinese simplified full mask' => [
                '你好世界',
                '*',
                '1-',
                '****',
            ],
            'chinese simplified middle range' => [
                '中国人民共和国',
                '#',
                '2-5',
                '中####和国',
            ],
            'chinese traditional' => [
                '您好世界',
                '*',
                '3-4',
                '您好**',
            ],
            'chinese last characters' => [
                '中文测试',
                '#',
                '-2',
                '中文##',
            ],
            // Thai tests
            'thai full mask' => [
                'สวัสดีชาวโลก',
                '*',
                '1-',
                '************',
            ],
            'thai specific range' => [
                'ขอบคุณครับ',
                '#',
                '2-5',
                'ข####ณครับ',
            ],
            'thai last characters' => [
                'ยินดีครับ',
                '*',
                '-4',
                'ยินดี****',
            ],
            // Hebrew tests
            'hebrew full mask' => [
                'שלום עולם',
                '*',
                '1-',
                '*********',
            ],
            'hebrew middle range' => [
                'בוקר טוב',
                '#',
                '2-4',
                'ב### טוב',
            ],
            'hebrew last characters' => [
                'מה שלומך',
                '*',
                '-3',
                'מה של***',
            ],
            // Mixed language tests
            'japanese and ascii' => [
                'user田中@example.com',
                '*',
                '1-@',
                '******@example.com',
            ],
            'chinese with english' => [
                'hello中国world',
                '#',
                '6-7',
                'hello##world',
            ],
            // RTL scripts complex scenarios
            'hebrew with numbers middle' => [
                'מספר12345',
                '*',
                '5-8',
                'מספר****5',
            ],
            'thai with special symbols' => [
                '@email@ทดสอบ.com',
                '#',
                '1-@',
                '#email@ทดสอบ.com',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForValidMaskRanges')]
    public function itShouldValidateValidMaskRanges(string $maskRanges): void
    {
        self::assertTrue($this->masker->isValidRange($maskRanges));
    }

    /** @return array<string, array{0: string}> */
    public static function providerForValidMaskRanges(): array
    {
        return [
            'numeric ranges' => ['1-3,5-7'],
            'character range' => ['1-@'],
            'no end' => ['2-'],
            'escaped numeral' => ['1-\5'],
            'to end pattern' => ['1-'],
            'to end from position 3' => ['3-'],
            'to end from position 5' => ['5-'],
            'multiple ranges with dynamic to end' => ['1-2,7-'],
            'mixed dynamic ranges' => ['3-5,-8'],
            'last N pattern' => ['-5'],
            'last one pattern' => ['-1'],
        ];
    }

    #[Test]
    #[DataProvider('providerForInvalidMaskRanges')]
    public function itShouldRejectInvalidMaskRanges(string $maskRanges): void
    {
        self::assertFalse($this->masker->isValidRange($maskRanges));
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

    #[Test]
    #[DataProvider('providerForInvalidMaskRanges')]
    public function itShouldThrowExceptionForInvalidMaskRanges(string $maskRanges): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid mask ranges provided');

        $this->masker->mask('input', $maskRanges);
    }
}
