<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\Modifiers\InvalidModifierPipeException;
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\StringFormatter\Test\Helper\TestingStringifier;

use function fopen;
use function uniqid;

#[CoversClass(StringifyModifier::class)]
final class StringifyModifierTest extends TestCase
{
    /** @return array<string, array{0: mixed}> */
    public static function providerForModifiableValues(): array
    {
        return [
            'array value' => [['a', 'b', 'c']],
            'integer value' => [42],
            'float value' => [3.14159],
            'boolean true' => [true],
            'boolean false' => [false],
            'null value' => [null],
            'object value' => [(object) ['key' => 'value']],
            'empty array' => [[]],
            'resource' => [fopen('php://memory', 'r')],
        ];
    }

    /** @return array<string, array{0: string}> */
    public static function providerForNonModifiableValues(): array
    {
        return [
            'string value' => ['test string'],
            'empty string' => [''],
        ];
    }

    #[Test]
    #[DataProvider('providerForModifiableValues')]
    public function itShouldDelegateToStringifierWhenValueIsNotString(mixed $value): void
    {
        $expected = uniqid();
        $stringifier = new TestingStringifier($expected);
        $modifier = new StringifyModifier($stringifier);

        $actual = $modifier->modify($value, null);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNonModifiableValues')]
    public function itShouldByPassTheStringifierWhenValueIsString(string $value): void
    {
        $modifier = new StringifyModifier(new TestingStringifier());

        $actual = $modifier->modify($value, null);

        self::assertSame($value, $actual);
    }

    #[Test]
    public function itShouldThrowExceptionWhenPipeIsNotNull(): void
    {
        $modifier = new StringifyModifier(new TestingStringifier());
        $pipe = 'existing_pipe_value';

        $this->expectException(InvalidModifierPipeException::class);
        $this->expectExceptionMessage('"existing_pipe_value" is not recognized as a valid pipe');

        $modifier->modify('test', $pipe);
    }
}
