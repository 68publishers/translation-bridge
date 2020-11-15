<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte\DI;

use Contributte\Translation\DI\TranslationProviderInterface;
use SixtyEightPublishers\TranslationBridge\DI\AbstractTranslationBridgeExtension;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\PrefixedTranslatorFactory;

final class TranslationBridgeExtension extends AbstractTranslationBridgeExtension implements TranslationProviderInterface
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
