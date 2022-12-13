<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte;

use Contributte\Translation\Translator;
use Contributte\Translation\LocalesResolvers\ResolverInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverRegistry;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class LocaleResolver implements ResolverInterface
{
	private readonly TranslatorLocaleResolverRegistry $translatorLocaleResolver;

	/**
	 * @param array<TranslatorLocaleResolverInterface> $resolvers
	 */
	public function __construct(array $resolvers = [])
	{
		$this->translatorLocaleResolver = new TranslatorLocaleResolverRegistry($resolvers);
	}

	public function resolve(Translator $translator): ?string
	{
		return $this->translatorLocaleResolver->resolveLocale($translator);
	}
}
