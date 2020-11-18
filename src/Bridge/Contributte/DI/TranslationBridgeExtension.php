<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte\DI;

use Nette\Localization\ITranslator;
use Contributte\Translation\DI\TranslationProviderInterface;
use SixtyEightPublishers\TranslationBridge\DI\AbstractTranslationBridgeExtension;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\PrefixedTranslatorFactory;
use SixtyEightPublishers\TranslationBridge\Bridge\SymfonyTranslation\Localization\TranslatorLocalizer;

final class TranslationBridgeExtension extends AbstractTranslationBridgeExtension implements TranslationProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$this->prefixedTranslatorFactoryDefinition->setFactory(PrefixedTranslatorFactory::class);
		$this->translatorLocalizerDefinition->setFactory(TranslatorLocalizer::class, [
			'@' . ITranslator::class,
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslationResources(): array
	{
		return $this->collectTranslationResources();
	}
}
