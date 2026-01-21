<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\Modifiers\ListModifier;
use Respect\StringFormatter\Test\Helper\TestingModifier;
use Respect\StringFormatter\Test\Helper\TestingTranslator;

#[CoversClass(ListModifier::class)]
final class ListModifierTest extends TestCase
{
    #[Test]
    #[DataProvider('providerNonSupportedValuesAndPipes')]
    public function itShouldDelegateToNextModifier(string|null $pipe, mixed $value): void
    {
        $nextModifier = new TestingModifier();

        $modifier = new ListModifier($nextModifier);

        $result = $modifier->modify($value, $pipe);

        self::assertSame($nextModifier->modify($value, $pipe), $result);
    }

    /** @return array<string, array{0: string|null, 1: mixed}> */
    public static function providerNonSupportedValuesAndPipes(): array
    {
        return [
            'pipe is null' => [null, ['a', 'b', 'c']],
            'pipe is not list' => ['notList', ['a', 'b', 'c']],
            'value is not array' => ['list:and', 'not an array'],
            'value is empty array' => ['list:or', []],
            'modifier is not well formatted' => ['list(and")', []],
        ];
    }

    /** @param array<int|string, string> $value */
    #[Test]
    #[DataProvider('providerSupportedValuesAndPipes')]
    public function itShouldModifyValue(string $pipe, array $value, string $expected): void
    {
        $modifier = new ListModifier(new TestingModifier());

        $result = $modifier->modify($value, $pipe);

        self::assertSame($expected, $result);
    }

    /** @return array<string, array{0: string, 1: array<int|string, string>, 2: string}> */
    public static function providerSupportedValuesAndPipes(): array
    {
        return [
            'with a single value' => [
                'list',
                ['apple'],
                'apple',
            ],
            ':and with a single value' => [
                'list:and',
                ['apple'],
                'apple',
            ],
            ':or with a single value' => [
                'list:or',
                ['apple'],
                'apple',
            ],
            'with two values' => [
                'list',
                ['apple', 'banana'],
                'apple and banana',
            ],
            ':and with two values' => [
                'list:and',
                ['apple', 'banana'],
                'apple and banana',
            ],
            ':or with two values' => [
                'list:or',
                ['apple', 'banana'],
                'apple or banana',
            ],
            'with multiple values' => [
                'list',
                ['apple', 'banana', 'cherry', 'date', 'elderberry'],
                'apple, banana, cherry, date, and elderberry',
            ],
            ':and with multiple values' => [
                'list:and',
                ['apple', 'banana', 'cherry', 'date', 'elderberry'],
                'apple, banana, cherry, date, and elderberry',
            ],
            ':or with multiple values' => [
                'list:or',
                ['apple', 'banana', 'cherry', 'date', 'elderberry'],
                'apple, banana, cherry, date, or elderberry',
            ],
            'with associative array' => [
                'list',
                ['a' => 'apple', 'b' => 'banana', 'c' => 'cherry', 'd' => 'date', 'e' => 'elderberry'],
                'apple, banana, cherry, date, and elderberry',
            ],
        ];
    }

    /** @param array<int|string, string> $value */
    #[Test]
    #[DataProvider('providerTranslatedConjunctions')]
    public function itShouldTranslateConjunctions(string $pipe, array $value, string $expected): void
    {
        $translator = new TestingTranslator(['and' => 'e', 'or' => 'ou']);

        $modifier = new ListModifier(new TestingModifier(), $translator);

        $result = $modifier->modify($value, $pipe);

        self::assertSame($expected, $result);
    }

    /** @return array<string, array{0: string, 1: array<int|string, string>, 2: string}> */
    public static function providerTranslatedConjunctions(): array
    {
        return [
            'translated and with two values' => [
                'list',
                ['maçã', 'banana'],
                'maçã e banana',
            ],
            'translated or with two values' => [
                'list:or',
                ['maçã', 'banana'],
                'maçã ou banana',
            ],
            'translated and with multiple values' => [
                'list:and',
                ['maçã', 'banana', 'cereja'],
                'maçã, banana, e cereja',
            ],
            'translated or with multiple values' => [
                'list:or',
                ['maçã', 'banana', 'cereja'],
                'maçã, banana, ou cereja',
            ],
        ];
    }
}
