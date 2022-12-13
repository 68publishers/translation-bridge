<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Localization;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use Nette\Localization\Translator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverRegistry;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

require __DIR__ . '/../bootstrap.php';

final class TranslatorLocaleResolverRegistryTest extends TestCase
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

		$proxy = new TranslatorLocaleResolverRegistry([$localeResolver1, $localeResolver2]);

		Assert::same('en', $proxy->resolveLocale($translator));
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

		$proxy = new TranslatorLocaleResolverRegistry([$localeResolver1, $localeResolver2]);

		Assert::null($proxy->resolveLocale($translator));
	}

	public function testLocaleShouldNotBeResolvedWithNoResolvers(): void
	{
		$translator = Mockery::mock(Translator::class);

		$proxy = new TranslatorLocaleResolverRegistry([]);

		Assert::null($proxy->resolveLocale($translator));
	}

	protected function tearDown(): void
	{
		Mockery::close();
	}
}

(new TranslatorLocaleResolverRegistryTest())->run();
