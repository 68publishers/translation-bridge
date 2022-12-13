<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Localization;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use InvalidArgumentException;
use Symfony\Component\Translation\Translator;
use SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizer;

require __DIR__ . '/../bootstrap.php';

final class TranslatorLocalizerTest extends TestCase
{
	public function testLocaleShouldBeSet(): void
	{
		$translator = Mockery::mock(Translator::class);

		$translator->shouldReceive('setLocale')
			->once()
			->with('en')
			->andReturns();

		$localizer = new TranslatorLocalizer($translator);

		$localizer->setLocale('en');
	}

	public function testExceptionShouldBeThrownIfLocaleCantBeSet(): void
	{
		$translator = Mockery::mock(Translator::class);

		$translator->shouldReceive('setLocale')
			->once()
			->with('en')
			->andThrows(new InvalidArgumentException(''));

		$localizer = new TranslatorLocalizer($translator);

		Assert::exception(
			static fn () => $localizer->setLocale('en'),
			InvalidLocaleException::class,
			'Invalid locale "en".'
		);
	}

	public function testLocaleShouldBeReturned(): void
	{
		$translator = Mockery::mock(Translator::class);

		$translator->shouldReceive('getLocale')
			->once()
			->withNoArgs()
			->andReturns('en');

		$localizer = new TranslatorLocalizer($translator);

		Assert::same('en', $localizer->getLocale());
	}

	public function testFallbackLocalesShouldBeReturned(): void
	{
		$translator = Mockery::mock(Translator::class);

		$translator->shouldReceive('getFallbackLocales')
			->once()
			->withNoArgs()
			->andReturns(['cs', 'en']);

		$localizer = new TranslatorLocalizer($translator);

		Assert::same(['cs', 'en'], $localizer->getFallbackLocales());
	}

	protected function tearDown(): void
	{
		Mockery::close();
	}
}

(new TranslatorLocalizerTest())->run();
