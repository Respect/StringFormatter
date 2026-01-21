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
use Respect\StringFormatter\Modifiers\InvalidModifierPipeException;
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\StringFormatter\Test\Helper\TestingStringifier;
use stdClass;

#[CoversClass(StringifyModifier::class)]
final class StringifyModifierTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForValues')]
    public function itShouldAlwaysStringifyValues(mixed $value, string $expected): void
    {
        $modifier = new StringifyModifier();

        $actual = $modifier->modify($value, null);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldUseCustomStringifierWhenProvided(): void
    {
        $stringifier = new TestingStringifier('custom value');
        $modifier = new StringifyModifier($stringifier);

        $actual = $modifier->modify('test', null);

        self::assertSame('custom value', $actual);
    }

    #[Test]
    #[DataProvider('providerForInvalidPipes')]
    public function itShouldThrowExceptionWhenPipeIsProvided(string $pipe): void
    {
        $modifier = new StringifyModifier();

        $this->expectException(InvalidModifierPipeException::class);
        $this->expectExceptionMessage('"' . $pipe . '" is not recognized as a valid pipe');

        $modifier->modify('value', $pipe);
    }

    /** @return array<string, array{0: mixed, 1: string}> */
    public static function providerForValues(): array
    {
        return [
            'string' => ['some string', '"some string"'],
            'integer' => [123, '123'],
            'float' => [123.456, '123.456'],
            'boolean true' => [true, '`true`'],
            'boolean false' => [false, '`false`'],
            'array' => [['not', 'scalar'], '`["not", "scalar"]`'],
            'object' => [new stdClass(), '`stdClass {}`'],
            'null' => [null, '`null`'],
        ];
    }

    /** @return array<string, array{0: string}> */
    public static function providerForInvalidPipes(): array
    {
        return [
            'raw' => ['raw'],
            'upper' => ['upper'],
            'lower' => ['lower'],
            'any pipe' => ['any_pipe'],
        ];
    }
}
