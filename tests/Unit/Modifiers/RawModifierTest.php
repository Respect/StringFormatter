<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\Modifiers\RawModifier;
use Respect\StringFormatter\Test\Helper\TestingModifier;
use stdClass;

#[CoversClass(RawModifier::class)]
final class RawModifierTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForScalarValuesWithRawPipe')]
    public function itShouldReturnScalarValuesAsRawStringWhenPipeIsRaw(mixed $value, string $expected): void
    {
        $modifier = new RawModifier(new TestingModifier());

        $actual = $modifier->modify($value, 'raw');

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForNonScalarValues')]
    public function itShouldDelegateToNextModifierWhenValueIsNotScalarAndPipeIsRaw(mixed $value): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new RawModifier($nextModifier);
        $expected = $nextModifier->modify($value, null);

        $actual = $modifier->modify($value, 'raw');

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForVariousValues')]
    public function itShouldDelegateToNextModifierWhenPipeIsNotRaw(mixed $value, string|null $pipe): void
    {
        $nextModifier = new TestingModifier();
        $modifier = new RawModifier($nextModifier);
        $expected = $nextModifier->modify($value, $pipe);

        $actual = $modifier->modify($value, $pipe);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: mixed, 1: string}> */
    public static function providerForScalarValuesWithRawPipe(): array
    {
        return [
            'string' => ['some string', 'some string'],
            'integer' => [123, '123'],
            'float' => [123.456, '123.456'],
            'boolean true' => [true, '1'],
            'boolean false' => [false, '0'],
        ];
    }

    /** @return array<string, array{0: mixed}> */
    public static function providerForNonScalarValues(): array
    {
        return [
            'array' => [['not', 'scalar']],
            'object' => [new stdClass()],
            'null' => [null],
        ];
    }

    /** @return array<string, array{0: mixed, 1: string|null}> */
    public static function providerForVariousValues(): array
    {
        return [
            'string with null pipe' => ['test string', null],
            'integer with null pipe' => [42, null],
            'array with null pipe' => [['a', 'b'], null],
            'object with null pipe' => [new stdClass(), null],
            'string with other pipe' => ['test', 'upper'],
            'integer with other pipe' => [42, 'lower'],
        ];
    }
}
