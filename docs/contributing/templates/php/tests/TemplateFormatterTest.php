<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\TemplateFormatter;

// THIS IS A TEMPLATE - Copy and rename this file for your formatter tests
#[CoversClass(TemplateFormatter::class)]
final class TemplateFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForValidFormattedString')]
    public function testShouldFormatString(string $input, string $expected): void
    {
        $formatter = new TemplateFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForValidFormattedString(): array
    {
        return [
            'empty strings' => ['', 'FORMATTED: '],
            'ascii chars' => ['Hello, World!', 'FORMATTED: Hello, World!'],
            'numbers' => ['1234567890', 'FORMATTED: 1234567890'],
            'mixed alphanumeric' => ['User123', 'FORMATTED: User123'],
            'special chars' => ['!@#$%^&*()', 'FORMATTED: !@#$%^&*()'],
        ];
    }
}
