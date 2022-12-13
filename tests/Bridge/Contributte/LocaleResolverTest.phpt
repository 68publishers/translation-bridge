<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Bridge\Contributte;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use Contributte\Translation\Translator;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\LocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

require __DIR__ . '/../../bootstrap.contributte.php';

final class LocaleResolverTest extends TestCase
{
	public function testLocaleShouldBeResolved(): void
	{
		$translator = Mockery::mock(Translator::class);
		$localeResolver1 = Mockery::mock(TranslatorLocaleResolverInterface::class);
		$localeResolver2 = Mockery::mock(TranslatorLocaleResolverInterface::class);

		$localeResolver1->shouldReceive('resolveLocale')
			->once()
			->with($translator)
			->andReturns(null);

		$localeResolver2->shouldReceive('resolveLocale')
			->once()
			->with($translator)
			->andReturns('en');

		$proxy = new LocaleResolver([$localeResolver1, $localeResolver2]);

		Assert::same('en', $proxy->resolve($translator));
	}

	public function testLocaleShouldNotBeResolved(): void
	{
		$translator = Mockery::mock(Translator::class);
		$localeResolver1 = Mockery::mock(TranslatorLocaleResolverInterface::class);
		$localeResolver2 = Mockery::mock(TranslatorLocaleResolverInterface::class);

		$localeResolver1->shouldReceive('resolveLocale')
			->once()
			->with($translator)
			->andReturns(null);

		$localeResolver2->shouldReceive('resolveLocale')
			->once()
			->with($translator)
			->andReturns(null);

		$proxy = new LocaleResolver([$localeResolver1, $localeResolver2]);

		Assert::null($proxy->resolve($translator));
	}

	public function testLocaleShouldNotBeResolvedWithNoResolvers(): void
	{
		$translator = Mockery::mock(Translator::class);

		$proxy = new LocaleResolver([]);

		Assert::null($proxy->resolve($translator));
	}

	protected function tearDown(): void
	{
		Mockery::close();
	}
}

(new LocaleResolverTest())->run();
