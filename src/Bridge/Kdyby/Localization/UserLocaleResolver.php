<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\Localization;

use Kdyby\Translation\Translator;
use Kdyby\Translation\IUserLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class UserLocaleResolver implements IUserLocaleResolver
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
	public function resolve(Translator $translator): ?string
	{
		return $this->translatorLocaleResolver->resolveLocale($translator);
	}
}
