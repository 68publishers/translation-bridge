<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte\Localization;

use Contributte\Translation\Translator;
use Contributte\Translation\LocaleResolver as ContributteLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class LocaleResolver extends ContributteLocaleResolver
{
	/** @var \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface  */
	private $translatorLocaleResolver;

	/**
	 * @param \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface $translatorLocaleResolver
	 */
	public function __construct(TranslatorLocaleResolverInterface $translatorLocaleResolver)
	{
		$this->translatorLocaleResolver = $translatorLocaleResolver;
	}

	/**
	 * {@inheritDoc}
	 */
	public function resolve(Translator $translator): string
	{
		return $this->translatorLocaleResolver->resolveLocale($translator);
	}
}
