<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte\Localization;

use Nette\Localization\ITranslator;
use Contributte\Translation\Translator as ContributteTranslator;
use Contributte\Translation\LocaleResolver as ContributteLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class ContributteTranslatorLocaleResolver implements TranslatorLocaleResolverInterface
{
	/** @var \Contributte\Translation\LocaleResolver  */
	private $resolver;

	/**
	 * @param \Contributte\Translation\LocaleResolver $resolver
	 */
	public function __construct(ContributteLocaleResolver $resolver)
	{
		$this->resolver = $resolver;
	}

	/**
	 * {@inheritDoc}
	 */
	public function resolveLocale(ITranslator $translator): ?string
	{
		return (function (ContributteTranslator $translator) {
			return $this->resolver->resolve($translator);
		})($translator);
	}
}
