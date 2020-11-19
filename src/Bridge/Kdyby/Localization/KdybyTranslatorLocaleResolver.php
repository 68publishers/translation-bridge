<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\Localization;

use Nette\Localization\ITranslator;
use Kdyby\Translation\IUserLocaleResolver;
use Kdyby\Translation\Translator as KdybyTranslator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class KdybyTranslatorLocaleResolver implements TranslatorLocaleResolverInterface
{
	/** @var \Kdyby\Translation\IUserLocaleResolver  */
	private $userLocaleResolver;

	/**
	 * @param \Kdyby\Translation\IUserLocaleResolver $userLocaleResolver
	 */
	public function __construct(IUserLocaleResolver $userLocaleResolver)
	{
		$this->userLocaleResolver = $userLocaleResolver;
	}

	/**
	 * {@inheritDoc}
	 */
	public function resolveLocale(ITranslator $translator): ?string
	{
		return (function (KdybyTranslator $translator) {
			return $this->userLocaleResolver->resolve($translator);
		})($translator);
	}
}
