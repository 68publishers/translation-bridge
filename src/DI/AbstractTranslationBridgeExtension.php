<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\DI;

use Nette\DI\CompilerExtension;
use Nette\Localization\ITranslator;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\FactoryDefinition;
use SixtyEightPublishers\TranslationBridge\TranslatorAwareInterface;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverRegistry;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

abstract class AbstractTranslationBridgeExtension extends CompilerExtension
{
	public const TAG_TRANSLATOR_LOCALE_RESOLVER = '68publishers.translation_bridge.translator_locale_resolver';

	/** @var \Nette\DI\Definitions\ServiceDefinition|NULL */
	protected $prefixedTranslatorFactoryDefinition;

	/** @var \Nette\DI\Definitions\ServiceDefinition|NULL */
	protected $translatorLocalizerDefinition;

	/** @var \Nette\DI\Definitions\ServiceDefinition|NULL */
	protected $translatorLocaleResolverDefinition;

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$this->prefixedTranslatorFactoryDefinition = $builder->addDefinition($this->prefix('prefixed_translator_factory'))
			->setType(PrefixedTranslatorFactoryInterface::class);

		$this->translatorLocalizerDefinition = $builder->addDefinition($this->prefix('translator_localizer'))
			->setType(TranslatorLocalizerInterface::class);

		$this->translatorLocaleResolverDefinition = $builder->addDefinition($this->prefix('translator_locale_resolver'))
			->setType(TranslatorLocaleResolverInterface::class)
			->setAutowired(FALSE);

		$this->registerTranslatorLocaleResolvers();
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile(): void
	{
		$this->beforeCompileProcessTranslatorAware();
		$this->beforeCompileProcessTranslatorLocaleResolver();
	}

	/**
	 * @return array
	 */
	protected function collectTranslationResources(): array
	{
		$resources = [];

		foreach ($this->compiler->getExtensions(TranslationProviderInterface::class) as $extension) {
			$resources[] = array_values($extension->getTranslationResources());
		}

		return array_merge([], ...$resources);
	}

	/**
	 * @return void
	 */
	protected function beforeCompileProcessTranslatorAware(): void
	{
		$builder = $this->getContainerBuilder();
		$translator = $builder->getByType(ITranslator::class, FALSE);

		if (NULL === $translator) {
			return;
		}

		$translator = $builder->getDefinition($translator);
		$definitions = array_filter($builder->getDefinitions(), static function (Definition $def): bool {
			return is_a($def->getType(), TranslatorAwareInterface::class, TRUE) || ($def instanceof FactoryDefinition && is_a($def->getResultType(), TranslatorAwareInterface::class, TRUE));
		});

		foreach ($definitions as $definition) {
			if ($definition instanceof FactoryDefinition) {
				$definition = $definition->getResultDefinition();
			}

			$definition->addSetup('setTranslator', [
				'translator' => $translator,
			]);
		}
	}

	/**
	 * @return void
	 */
	protected function beforeCompileProcessTranslatorLocaleResolver(): void
	{
		$builder = $this->getContainerBuilder();
		$services = $builder->findByTag(self::TAG_TRANSLATOR_LOCALE_RESOLVER);

		uasort($services, static function ($a, $b) {
			return (is_int($b) ? $b : 0) <=> (is_int($a) ? $a : 0);
		});

		$this->translatorLocaleResolverDefinition->setFactory(TranslatorLocaleResolverRegistry::class, [
			array_map(static function (string $serviceName) use ($builder) {
				return $builder->getDefinition($serviceName);
			}, array_keys($services)),
		]);
	}

	/**
	 * @return void
	 */
	private function registerTranslatorLocaleResolvers(): void
	{
		$builder = $this->getContainerBuilder();
		$i = 0;

		/** @var \SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolverProviderInterface $extension */
		foreach ($this->compiler->getExtensions(TranslatorLocaleResolverProviderInterface::class) as $extension) {
			foreach ($extension->getTranslatorLocaleResolvers() as $translatorLocaleResolver) {
				$builder->addDefinition($this->prefix('translation_locale_resolver.extension.' . $i++))
					->setType(TranslatorLocaleResolverInterface::class)
					->setFactory($translatorLocaleResolver->factory)
					->setAutowired(FALSE)
					->addTag(self::TAG_TRANSLATOR_LOCALE_RESOLVER, $translatorLocaleResolver->priority);
			}
		}
	}
}
