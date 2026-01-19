<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\BypassTranslator;

#[CoversClass(BypassTranslator::class)]
final class BypassTranslatorTest extends TestCase
{
    private BypassTranslator $translator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translator = new BypassTranslator();
    }

    #[Test]
    public function itShouldReturnOriginalIdForTranslation(): void
    {
        $id = 'some.translation.key';
        $parameters = ['param1' => 'value1'];
        $domain = 'messages';
        $locale = 'en';

        $result = $this->translator->trans($id, $parameters, $domain, $locale);

        self::assertSame($id, $result);
    }

    #[Test]
    public function itShouldReturnOriginalIdForTranslationWithMinimalParameters(): void
    {
        $id = 'simple.key';

        $result = $this->translator->trans($id);

        self::assertSame($id, $result);
    }

    #[Test]
    public function itShouldReturnOriginalIdForTranslationWithEmptyParameters(): void
    {
        $id = 'key.with.no.params';

        $result = $this->translator->trans($id, []);

        self::assertSame($id, $result);
    }

    #[Test]
    public function itShouldReturnOriginalIdForTranslationWithNullDomainAndLocale(): void
    {
        $id = 'key.with.nulls';

        $result = $this->translator->trans($id, ['param' => 'value'], null, null);

        self::assertSame($id, $result);
    }

    #[Test]
    public function itShouldAlwaysReturnEnglishAsDefaultLocale(): void
    {
        $locale = $this->translator->getLocale();

        self::assertSame('en', $locale);
    }

    #[Test]
    public function itShouldHandleEmptyStringTranslation(): void
    {
        $result = $this->translator->trans('');

        self::assertSame('', $result);
    }

    #[Test]
    public function itShouldHandleComplexTranslationKeyId(): void
    {
        $complexId = 'nested.deep.very.complex.translation.key.with.dots';

        $result = $this->translator->trans($complexId, ['param' => 'value'], 'domain', 'fr_FR');

        self::assertSame($complexId, $result);
    }

    #[Test]
    public function itShouldHandleSpecialCharactersInTranslationKey(): void
    {
        $specialId = 'key.with-special_chars@123#$%';

        $result = $this->translator->trans($specialId);

        self::assertSame($specialId, $result);
    }
}
