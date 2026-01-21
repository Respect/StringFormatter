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
use Respect\StringFormatter\Modifiers\TransModifier;
use Respect\StringFormatter\Test\Helper\TestingModifier;
use Respect\StringFormatter\Test\Helper\TestingTranslator;

use function is_string;

#[CoversClass(TransModifier::class)]
final class TransModifierTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForDelegationCases')]
    public function itShouldDelegateToNextModifierWhenConditionsNotMet(mixed $value, string|null $pipe): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new TransModifier($nextModifier, new TestingTranslator([]));

        // For non-string values with 'trans' pipe, expect delegation with null pipe
        $expectedPipe = $pipe === 'trans' && !is_string($value) ? null : $pipe;
        $expected = $nextModifier->modify($value, $expectedPipe);

        $actual = $modifier->modify($value, $pipe);

        self::assertSame($expected, $actual);
    }

    /** @param array<string, string> $translations */
    #[Test]
    #[DataProvider('providerForTranslationCases')]
    public function itShouldTranslateUsingCustomTranslator(string $value, string $expected, array $translations): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new TransModifier($nextModifier, new TestingTranslator($translations));
        $pipe = 'trans';

        $actual = $modifier->modify($value, $pipe);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldDelegateToNextModifierWhenTranslationNotFound(): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new TransModifier($nextModifier, new TestingTranslator([
            'hello' => 'Hello World',
            'welcome' => 'Welcome',
        ]));
        $unknownKey = 'nonexistent_key';
        $pipe = 'trans';
        $expected = $nextModifier->modify($unknownKey, null);

        $actual = $modifier->modify($unknownKey, $pipe);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: array<string, string>}> */
    public static function providerForTranslationCases(): array
    {
        return [
            'hello translation' => ['hello', 'Hello World', ['hello' => 'Hello World']],
            'welcome translation' => ['welcome', 'Welcome', ['welcome' => 'Welcome']],
            'goodbye translation' => ['goodbye', 'Goodbye', ['goodbye' => 'Goodbye']],
            'empty string' => ['', '', ['' => '']],
            'multiple translations available' => [
                'hello',
                'Hello World',
                [
                    'hello' => 'Hello World',
                    'welcome' => 'Welcome',
                    'goodbye' => 'Goodbye',
                ],
            ],
        ];
    }

    /** @return array<string, array{0: mixed, 1: string|null}> */
    public static function providerForDelegationCases(): array
    {
        return [
            'pipe is not trans' => ['hello', 'notTrans'],
            'pipe is null' => ['hello', null],
            'value is array' => [['a', 'b'], 'trans'],
            'value is integer' => [42, 'trans'],
            'value is float' => [3.14, 'trans'],
            'value is boolean true' => [true, 'trans'],
            'value is boolean false' => [false, 'trans'],
            'value is null' => [null, 'trans'],
            'value is object' => [(object) ['key' => 'value'], 'trans'],
        ];
    }

    /** @return array<string, array{0: mixed}> */
    public static function providerForNonStringValues(): array
    {
        return [
            'array' => [['a', 'b']],
            'integer' => [42],
            'float' => [3.14],
            'boolean true' => [true],
            'boolean false' => [false],
            'null' => [null],
            'object' => [(object) ['key' => 'value']],
        ];
    }
}
