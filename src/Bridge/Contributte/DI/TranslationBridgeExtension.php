<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte\DI;

use Nette\DI\Definitions\Statement;
use Nette\Localization\ITranslator;
use Contributte\Translation\DI\TranslationExtension;
use Contributte\Translation\DI\TranslationProviderInterface;
use SixtyEightPublishers\TranslationBridge\Exception\RuntimeException;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolver;
use SixtyEightPublishers\TranslationBridge\DI\AbstractTranslationBridgeExtension;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\PrefixedTranslatorFactory;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolverProviderInterface;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\Localization\LocaleResolver;
use SixtyEightPublishers\TranslationBridge\Bridge\SymfonyTranslation\Localization\TranslatorLocalizer;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\Localization\ContributteTranslatorLocaleResolver;

final class TranslationBridgeExtension extends AbstractTranslationBridgeExtension implements TranslationProviderInterface, TranslatorLocaleResolverProviderInterface
{
	public const PRIORITY_CONTRIBUTTE_TRANSLATION_LOCALE_RESOLVER = 10;

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		if (empty($this->compiler->getExtensions(TranslationExtension::class))) {
			throw new RuntimeException(sprintf(
				'Please register extension %s.',
				TranslationExtension::class
			));
		}

		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();

		$this->prefixedTranslatorFactoryDefinition->setFactory(PrefixedTranslatorFactory::class);
		$this->translatorLocalizerDefinition->setFactory(TranslatorLocalizer::class, [
			'@' . ITranslator::class,
		]);

		$builder->addDefinition($this->prefix('locale_resolver'))
			->setType(LocaleResolver::class)
			->setArguments([
				sprintf('@%s.localeResolver', $this->getIntegratedExtensionName()),
				$this->translatorLocaleResolverDefinition,
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslationResources(): array
	{
		return $this->collectTranslationResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslatorLocaleResolvers(): array
	{
		return [
			new TranslatorLocaleResolver(
				new Statement(ContributteTranslatorLocaleResolver::class, [sprintf('@%s.localeResolver', $this->getIntegratedExtensionName())]),
				self::PRIORITY_CONTRIBUTTE_TRANSLATION_LOCALE_RESOLVER
			),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function beforeCompileProcessTranslatorLocaleResolver(): void
	{
		parent::beforeCompileProcessTranslatorLocaleResolver();

		$builder = $this->getContainerBuilder();

		/** @var \Nette\DI\Definitions\ServiceDefinition $translator */
		$translator = $builder->getDefinitionByType(ITranslator::class);

		$translator->getFactory()->arguments['localeResolver'] = $builder->getDefinition($this->prefix('locale_resolver'));
	}

	/**
	 * @return string|NULL
	 */
	private function getIntegratedExtensionName(): ?string
	{
		$extension = $this->compiler->getExtensions(TranslationExtension::class);

		return key($extension);
	}
}
