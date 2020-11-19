<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte\Localization;

use Contributte\Translation\Translator;
use Contributte\Translation\LocalesResolvers\ResolverInterface;
use Contributte\Translation\LocaleResolver as ContributteLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class LocaleResolver extends ContributteLocaleResolver
{
	/** @var \Contributte\Translation\LocaleResolver  */
	private $inner;

	/** @var \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface  */
	private $translatorLocaleResolver;

	/**
	 * @param \Contributte\Translation\LocaleResolver                                                $inner
	 * @param \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface $translatorLocaleResolver
	 */
	public function __construct(ContributteLocaleResolver $inner, TranslatorLocaleResolverInterface $translatorLocaleResolver)
	{
		$this->inner = $inner;
		$this->translatorLocaleResolver = $translatorLocaleResolver;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getResolvers(): array
	{
		return $this->inner->getResolvers();
	}

	/**
	 * {@inheritDoc}
	 */
	public function addResolver(ResolverInterface $resolver): ContributteLocaleResolver
	{
		return $this->inner->addResolver($resolver);
	}

	/**
	 * {@inheritDoc}
	 */
	public function resolve(Translator $translator): string
	{
		return $this->translatorLocaleResolver->resolveLocale($translator);
	}
}
