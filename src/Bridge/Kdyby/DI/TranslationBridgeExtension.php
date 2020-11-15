<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\DI;

use Kdyby\Translation\DI\ITranslationProvider;
use SixtyEightPublishers\TranslationBridge\DI\AbstractTranslationBridgeExtension;
use SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\PrefixedTranslatorFactory;

final class TranslationBridgeExtension extends AbstractTranslationBridgeExtension implements ITranslationProvider
{
	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$this->prefixedTranslatorFactoryDefinition->setFactory(PrefixedTranslatorFactory::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslationResources(): array
	{
		return $this->collectTranslationResources();
	}
}
