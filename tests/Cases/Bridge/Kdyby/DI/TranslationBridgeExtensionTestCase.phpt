<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\Kdyby\DI;

use Nette\Configurator;
use Nette\DI\Container;
use Nette\Localization\ITranslator;
use Kdyby\Translation\ITranslator as KdybyTranslator;
use SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\AbstractTranslationBridgeExtensionTestCase;

require __DIR__ . '/../../../../bootstrap.kdyby.php';

class TranslationBridgeExtensionTestCase extends AbstractTranslationBridgeExtensionTestCase
{
	/**
	 * {@inheritDoc}
	 */
	protected function setupContainer(Configurator $configurator): void
	{
		$configurator->addConfig(CONFIG_DIR . '/kdyby.config.neon');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getTranslatorService(Container $container): ITranslator
	{
		return $container->getByType(KdybyTranslator::class);
	}
}

(new TranslationBridgeExtensionTestCase())->run();
