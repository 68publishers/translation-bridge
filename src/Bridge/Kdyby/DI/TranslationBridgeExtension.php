<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\DI;

use Kdyby\Translation\Translator;
use Kdyby\Translation\ITranslator;
use Nette\DI\Definitions\Statement;
use Kdyby\Translation\IUserLocaleResolver;
use Kdyby\Translation\DI\ITranslationProvider;
use Kdyby\Translation\DI\TranslationExtension;
use SixtyEightPublishers\TranslationBridge\Exception\RuntimeException;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolver;
use SixtyEightPublishers\TranslationBridge\DI\AbstractTranslationBridgeExtension;
use SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\PrefixedTranslatorFactory;
use SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\Localization\UserLocaleResolver;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolverProviderInterface;
use SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\Localization\KdybyTranslatorLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Bridge\SymfonyTranslation\Localization\TranslatorLocalizer;

final class TranslationBridgeExtension extends AbstractTranslationBridgeExtension implements ITranslationProvider, TranslatorLocaleResolverProviderInterface
{
	public const PRIORITY_KDYBY_TRANSLATION_LOCALE_RESOLVER = 10;

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

		$builder->addDefinition($this->prefix('user_locale_resolver'))
			->setType(IUserLocaleResolver::class)
			->setFactory(UserLocaleResolver::class, [$this->translatorLocaleResolverDefinition]);
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
				new Statement(KdybyTranslatorLocaleResolver::class, [sprintf('@%s.userLocaleResolver', $this->getIntegratedExtensionName())]),
				self::PRIORITY_KDYBY_TRANSLATION_LOCALE_RESOLVER
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
		$translator = $builder->getDefinitionByType(Translator::class);

		$translator->getFactory()->arguments[0] = $builder->getDefinition($this->prefix('user_locale_resolver'));
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
