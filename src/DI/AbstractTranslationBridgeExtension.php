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

abstract class AbstractTranslationBridgeExtension extends CompilerExtension
{
	/** @var \Nette\DI\Definitions\ServiceDefinition|NULL */
	protected $prefixedTranslatorFactoryDefinition;

	/** @var \Nette\DI\Definitions\ServiceDefinition|NULL */
	protected $translatorLocalizerDefinition;

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
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile(): void
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
}
