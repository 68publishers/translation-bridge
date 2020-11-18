<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\SymfonyTranslation\Localization;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Exception\InvalidArgumentException;
use SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException;
use SixtyEightPublishers\TranslationBridge\Bridge\SymfonyTranslation\Localization\TranslatorLocalizer;

require __DIR__ . '/../../../../bootstrap.symfony-translation.php';

class TranslatorLocalizerTestCase extends TestCase
{
	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		Mockery::close();
	}

	/**
	 * @return void
	 */
	public function testLocaleIsReturned() : void
	{
		$localeAware = Mockery::mock(Translator::class);
		$localizer = new TranslatorLocalizer($localeAware);

		$localeAware->shouldReceive('getLocale')
			->once()
			->andReturn('en_US');

		Assert::same('en_US', $localizer->getLocale());
	}

	/**
	 * @return void
	 */
	public function testSetLocale() : void
	{
		$localeAware = Mockery::mock(Translator::class);
		$localizer = new TranslatorLocalizer($localeAware);

		$localeAware->shouldReceive('setLocale')
			->once()
			->with('cs_CZ')
			->andReturnNull();

		$localeAware->shouldReceive('setLocale')
			->once()
			->with('{invalid}')
			->andThrow(new InvalidArgumentException());

		Assert::noError(static function () use ($localizer) {
			$localizer->setLocale('cs_CZ');
		});

		Assert::throws(static function () use ($localizer) {
			$localizer->setLocale('{invalid}');
		}, InvalidLocaleException::class, 'Invalid locale {invalid}.');
	}
}

(new TranslatorLocalizerTestCase())->run();
