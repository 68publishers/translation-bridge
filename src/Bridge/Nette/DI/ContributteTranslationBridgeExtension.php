<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Nette\DI;

use RuntimeException;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Contributte\Translation\DI\TranslationExtension;
use Contributte\Translation\LocaleResolver as ContributteLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizer;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\LocaleResolver;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\PrefixedTranslatorFactory;
use Contributte\Translation\DI\TranslationProviderInterface as ContributteTranslationProviderInterface;
use function count;
use function assert;
use function sprintf;
use function array_values;

final class ContributteTranslationBridgeExtension extends CompilerExtension implements ContributteTranslationProviderInterface
{
	private ExtensionHelper $helper;

	public function loadConfiguration(): void
	{
		$this->helper = new ExtensionHelper($this, $this->compiler);

		if (0 >= count($this->compiler->getExtensions(TranslationExtension::class))) {
			throw new RuntimeException(sprintf(
				'Please register the compiler extension of type %s.',
				TranslationExtension::class
			));
		}

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('prefixed_translator_factory'))
			->setType(PrefixedTranslatorFactoryInterface::class)
			->setFactory(PrefixedTranslatorFactory::class);

		$builder->addDefinition($this->prefix('translator_localizer'))
			->setType(TranslatorLocalizerInterface::class)
			->setFactory(TranslatorLocalizer::class);

		$builder->addDefinition($this->prefix('locale_resolver'))
			->setType(LocaleResolver::class);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$localeResolver = $builder->getDefinition($this->prefix('locale_resolver'));
		$contributteLocaleResolver = $builder->getDefinitionByType(ContributteLocaleResolver::class);
		assert($localeResolver instanceof ServiceDefinition && $contributteLocaleResolver instanceof ServiceDefinition);

		$resolvers = $this->helper->collectLocaleResolvers();

		if (0 < count($resolvers)) {
			$localeResolver->setArguments([
				array_values($resolvers),
			]);

			$contributteLocaleResolver->addSetup('addResolver', [
				LocaleResolver::class,
			]);
		} else {
			$builder->removeDefinition($this->prefix('locale_resolver'));
		}

		$this->helper->awareTranslator();
	}

	/**
	 * @return array<string>
	 */
	public function getTranslationResources(): array
	{
		return $this->helper->collectTranslationResources();
	}
}
