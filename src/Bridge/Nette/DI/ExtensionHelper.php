<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Nette\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Localization\Translator;
use Nette\DI\Definitions\Reference;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use SixtyEightPublishers\TranslationBridge\TranslatorAwareInterface;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;
use function is_a;
use function assert;
use function array_merge;
use function array_filter;
use function array_values;

final class ExtensionHelper
{
	public function __construct(
		private readonly CompilerExtension $extension,
		private readonly Compiler $compiler,
	) {
	}

	/**
	 * @return array<string>
	 */
	public function collectTranslationResources(): array
	{
		$resources = [];

		foreach ($this->compiler->getExtensions(TranslationProviderInterface::class) as $extension) {
			assert($extension instanceof TranslationProviderInterface);
			$resources[] = array_values($extension->getTranslationResources());
		}

		return array_merge(...$resources);
	}

	/**
	 * @return array<Definition>
	 */
	public function collectLocaleResolvers(): array
	{
		return $this->extension->getContainerBuilder()->findByType(TranslatorLocaleResolverInterface::class);
	}

	public function awareTranslator(): void
	{
		$builder = $this->extension->getContainerBuilder();
		$prefixedTranslatorFactoryName = $builder->getByType(PrefixedTranslatorFactoryInterface::class);

		$definitions = array_filter(
			$builder->getDefinitions(),
			static fn (Definition $def): bool =>
				null !== $def->getType()
				&& (
					is_a($def->getType(), TranslatorAwareInterface::class, true)
					|| ($def instanceof FactoryDefinition && null !== $def->getResultType() && is_a($def->getResultType(), TranslatorAwareInterface::class, true))
				)
		);

		foreach ($definitions as $definition) {
			if ($definition instanceof FactoryDefinition) {
				$definition = $definition->getResultDefinition();
			}

			assert($definition instanceof ServiceDefinition);

			$definition->addSetup('setTranslator', [
				new Reference(Translator::class),
				null !== $prefixedTranslatorFactoryName ? new Reference($prefixedTranslatorFactoryName) : null,
			]);
		}
	}
}
