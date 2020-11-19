<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class SimpleTranslatorLocaleResolver implements TranslatorLocaleResolverInterface
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
	public function resolveLocale(ITranslator $translator): ?string
	{
		return $this->locale;
	}
}
