<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge;

use Tester\Assert;
use Tester\TestCase;
use Nette\Configurator;
use Nette\DI\Container;
use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableService;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableServiceFactoryInterface;

abstract class AbstractTranslationBridgeExtensionTestCase extends TestCase
{
	/**
	 * @param \Nette\Configurator $configurator
	 */
	abstract protected function setupContainer(Configurator $configurator): void;

	/**
	 * @return \Nette\DI\Container
	 */
	protected function createContainer(): Container
	{
		$configurator = new Configurator();

		$configurator->setTempDirectory(TEMP_PATH);
		$configurator->addConfig(CONFIG_DIR . '/common.neon');

		$this->setupContainer($configurator);

		return $configurator->createContainer();
	}

	/**
	 * @return void
	 */
	public function testCompilerExtensionIntegration(): void
	{
		/** @var \Nette\DI\Container|NULL $container */
		$container = NULL;

		# create container
		Assert::noError(function () use (&$container) {
			$container = $this->createContainer();
		});

		# test provided translations
		$translator = $container->getByType(ITranslator::class);

		Assert::same('bar', $translator->translate('test_provider.foo'));

		# test translator aware
		$service = $container->getByType(TranslatableService::class);
		$serviceFactory = $container->getByType(TranslatableServiceFactoryInterface::class);

		Assert::type(ITranslator::class, $service->getTranslator());
		Assert::type(ITranslator::class, $serviceFactory->create()->getTranslator());

		# test prefixed translator factory
		$prefixedTranslatorFactory = $container->getByType(PrefixedTranslatorFactoryInterface::class);
		$prefixedTranslator = $prefixedTranslatorFactory->create('test_provider');

		Assert::same('bar', $prefixedTranslator->translate('foo'));
	}
}
