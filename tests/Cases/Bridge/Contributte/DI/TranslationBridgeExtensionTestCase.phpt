<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\Contributte\DI;

use Nette\Configurator;
use Nette\DI\Container;
use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\AbstractTranslationBridgeExtensionTestCase;

require __DIR__ . '/../../../../bootstrap.contributte.php';

class TranslationBridgeExtensionTestCase extends AbstractTranslationBridgeExtensionTestCase
{
	/**
	 * {@inheritDoc}
	 */
	protected function setupContainer(Configurator $configurator): void
	{
		$configurator->addConfig(CONFIG_DIR . '/contributte.config.neon');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getTranslatorService(Container $container): ITranslator
	{
		return $container->getService('translation.translator');
	}
}

(new TranslationBridgeExtensionTestCase())->run();
