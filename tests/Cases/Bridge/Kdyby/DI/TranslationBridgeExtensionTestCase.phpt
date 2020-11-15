<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Bridge\Kdyby\DI;

use Nette\Configurator;
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
}

(new TranslationBridgeExtensionTestCase())->run();
