<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\SymfonyTranslation\Localization;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use Symfony\Component\Translation\Translator;
use SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException;
use SixtyEightPublishers\TranslationBridge\Bridge\SymfonyTranslation\Localization\TranslatorLocalizer;

require __DIR__ . '/../../../../bootstrap.symfony-translation.php';

class TranslatorLocalizerTestCase extends TestCase
{
	/** @var \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface|NULL */
	private $localizer;

	/**
	 * @return void
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$translator = new Translator('cs_CZ');
		$this->localizer = new TranslatorLocalizer($translator);

		$translator->setFallbackLocales(['en_US']);
	}

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
	public function testLocaleAndFallbackAreReturned(): void
	{
		Assert::same('cs_CZ', $this->localizer->getLocale());
		Assert::same(['en_US'], $this->localizer->getFallbackLocales());
	}

	/**
	 * @return void
	 */
	public function testSetLocale(): void
	{
		Assert::noError(function () {
			$this->localizer->setLocale('en_US');
		});

		Assert::throws(function () {
			$this->localizer->setLocale('{invalid}');
		}, InvalidLocaleException::class, 'Invalid locale {invalid}.');
	}
}

(new TranslatorLocalizerTestCase())->run();
