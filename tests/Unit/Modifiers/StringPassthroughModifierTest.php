<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\Modifiers\StringPassthroughModifier;
use Respect\StringFormatter\Test\Helper\TestingModifier;

use function fopen;
use function uniqid;

#[CoversClass(StringPassthroughModifier::class)]
final class StringPassthroughModifierTest extends TestCase
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
            'control characters' => ["\n\r\t"],
        ];
    }

    #[Test]
    #[DataProvider('providerForModifiableValues')]
    public function itShouldDelegateToStringifierWhenValueIsNotString(mixed $value): void
    {
        $expected = uniqid();
        $modifier = new StringPassthroughModifier(new TestingModifier($expected));

        $actual = $modifier->modify($value, null);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNonModifiableValues')]
    public function itShouldByPassTheStringifierWhenValueIsString(string $value): void
    {
        $modifier = new StringPassthroughModifier(new TestingModifier());

        $actual = $modifier->modify($value, null);

        self::assertSame($value, $actual);
    }
}
