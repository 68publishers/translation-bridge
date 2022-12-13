<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Bridge\Contributte;

use Error;
use Mockery;
use Tester\Assert;
use Tester\TestCase;
use RuntimeException;
use Nette\Localization\Translator;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableService;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableServiceWithCustomPrefix;

require __DIR__ . '/bootstrap.php';

final class TranslatorAwareTraitTest extends TestCase
{
	public function testErrorShouldBeThrownIfTranslatorNotProvided(): void
	{
		$service = new TranslatableService();

		Assert::exception(
			static fn () => $service->getTranslator(),
			Error::class,
			'Typed property SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableService::$translator must not be accessed before initialization'
		);
	}

	public function testTranslatorShouldBeProvided(): void
	{
		$translator = Mockery::mock(Translator::class);
		$service = new TranslatableService();

		$service->setTranslator($translator);

		Assert::same($translator, $service->getTranslator());
	}

	public function testExceptionShouldBeThrownIfPrefixedTranslatorFactoryNotProvided(): void
	{
		$translator = Mockery::mock(Translator::class);
		$service = new TranslatableService();

		$service->setTranslator($translator);

		Assert::exception(
			static fn () => $service->getPrefixedTranslator(),
			RuntimeException::class,
			'Please set prefixed translator factory through method SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableService::setTranslator().'
		);
	}

	public function testPrefixedTranslatorShouldBeCreatedWithDefaultPrefixStrategy(): void
	{
		$translator = Mockery::mock(Translator::class);
		$prefixedTranslator = Mockery::mock(Translator::class);
		$prefixedTranslatorFactory = Mockery::mock(PrefixedTranslatorFactoryInterface::class);

		$service = new TranslatableService();

		$prefixedTranslatorFactory->shouldReceive('create')
			->once()
			->with('SixtyEightPublishers_TranslationBridge_Tests_Fixtures_TranslatableService')
			->andReturns($prefixedTranslator);

		$service->setTranslator($translator, $prefixedTranslatorFactory);

		Assert::same($prefixedTranslator, $service->getPrefixedTranslator());
	}

	public function testPrefixedTranslatorShouldBeCreatedWithCustomPrefix(): void
	{
		$translator = Mockery::mock(Translator::class);
		$prefixedTranslator = Mockery::mock(Translator::class);
		$prefixedTranslatorFactory = Mockery::mock(PrefixedTranslatorFactoryInterface::class);

		$service = new TranslatableServiceWithCustomPrefix('prefix');

		$prefixedTranslatorFactory->shouldReceive('create')
			->once()
			->with('prefix')
			->andReturns($prefixedTranslator);

		$service->setTranslator($translator, $prefixedTranslatorFactory);

		Assert::same($prefixedTranslator, $service->getPrefixedTranslator());
	}

	protected function tearDown(): void
	{
		Mockery::close();
	}
}

(new TranslatorAwareTraitTest())->run();
