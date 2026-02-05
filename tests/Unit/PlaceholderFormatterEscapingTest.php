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
use Respect\StringFormatter\PlaceholderFormatter;

#[CoversClass(PlaceholderFormatter::class)]
final class PlaceholderFormatterEscapingTest extends TestCase
{
    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForEscapedColons')]
    public function itShouldHandleEscapedColonsInFormatterArguments(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForEscapedColons(): array
    {
        return [
            'pattern with escaped colon' => [
                ['time' => '1234'],
                '{{time|pattern:##\:##}}',
                '12:34',
            ],
            'pattern with multiple escaped colons' => [
                ['time' => '123456'],
                '{{time|pattern:##\:##\:##}}',
                '12:34:56',
            ],
        ];
    }

    /** @param array<string, mixed> $parameters */
    #[Test]
    #[DataProvider('providerForEscapedPipes')]
    public function itShouldHandleEscapedPipesInFormatterArguments(
        array $parameters,
        string $template,
        string $expected,
    ): void {
        $formatter = new PlaceholderFormatter($parameters);
        $actual = $formatter->format($template);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: array<string, mixed>, 1: string, 2: string}> */
    public static function providerForEscapedPipes(): array
    {
        return [
            'pattern with escaped pipe' => [
                ['value' => '123456'],
                '{{value|pattern:###\|###}}',
                '123|456',
            ],
            'pattern with multiple escaped pipes' => [
                ['value' => '12345678'],
                '{{value|pattern:##\|##\|##\|##}}',
                '12|34|56|78',
            ],
        ];
    }
}
