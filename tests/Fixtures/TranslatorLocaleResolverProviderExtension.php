<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolver;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolverProviderInterface;

final class TranslatorLocaleResolverProviderExtension extends CompilerExtension implements TranslatorLocaleResolverProviderInterface
{
	/** @var string  */
	private $locale;

	/**
	 * @param string $locale
	 */
	public function __construct(string $locale)
	{
		$this->locale = $locale;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslatorLocaleResolvers(): array
	{
		return [
			new TranslatorLocaleResolver(new Statement(SimpleTranslatorLocaleResolver::class, [$this->locale]), 15),
		];
	}
}
