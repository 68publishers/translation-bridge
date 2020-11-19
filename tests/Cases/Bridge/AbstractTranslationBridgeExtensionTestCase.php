<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge;

use Tester\Assert;
use Tester\TestCase;
use Nette\Configurator;
use Nette\DI\Container;
use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableService;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableServiceFactoryInterface;

abstract class AbstractTranslationBridgeExtensionTestCase extends TestCase
{
	/**
	 * @return void
	 */
	public function testTranslations(): void
	{
		$container = $this->createContainer(CONFIG_DIR . '/translations.neon');
		$translator = $container->getByType(ITranslator::class);

		Assert::same('bar', $translator->translate('test_provider.foo'));

		$prefixedTranslatorFactory = $container->getByType(PrefixedTranslatorFactoryInterface::class);
		$prefixedTranslator = $prefixedTranslatorFactory->create('test_provider');

		Assert::same('bar', $prefixedTranslator->translate('foo'));
	}

	/**
	 * @return void
	 */
	public function testLocalization(): void
	{
		$container = $this->createContainer(CONFIG_DIR . '/localization.neon');
		$translatorLocalizer = $container->getByType(TranslatorLocalizerInterface::class);

		Assert::same('fr_FR', $translatorLocalizer->getLocale()); # resolved

		Assert::noError(static function () use ($translatorLocalizer) {
			$translatorLocalizer->setLocale('cs_CZ');
		});

		Assert::same('cs_CZ', $translatorLocalizer->getLocale());

		Assert::throws(static function () use ($translatorLocalizer) {
			$translatorLocalizer->setLocale('{invalid}');
		}, InvalidLocaleException::class);
	}

	/**
	 * @return void
	 */
	public function testTranslatorAware(): void
	{
		$container = $this->createContainer(CONFIG_DIR . '/translator-aware.neon');
		$service = $container->getByType(TranslatableService::class);
		$serviceFactory = $container->getByType(TranslatableServiceFactoryInterface::class);

		Assert::type(ITranslator::class, $service->getTranslator());
		Assert::type(ITranslator::class, $serviceFactory->create()->getTranslator());
	}

	/**
	 * @param \Nette\Configurator $configurator
	 */
	abstract protected function setupContainer(Configurator $configurator): void;

	/**
	 * @param string|NULL $config
	 *
	 * @return \Nette\DI\Container
	 */
	protected function createContainer(?string $config = NULL): Container
	{
		$configurator = new Configurator();

		$configurator->setTempDirectory(TEMP_PATH);

		if (NULL !== $config) {
			$configurator->addConfig($config);
		}

		$this->setupContainer($configurator);

		/** @var \Nette\DI\Container|NULL $container */
		$container = NULL;

		# create container
		Assert::noError(static function () use (&$container, $configurator) {
			$container = $configurator->createContainer();
		});

		return $container;
	}
}
