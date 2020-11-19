<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

use Nette\Localization\ITranslator;

interface TranslatorLocaleResolverInterface
{
	/**
	 * @param \Nette\Localization\ITranslator $translator
	 *
	 * @return string|NULL
	 */
	public function resolveLocale(ITranslator $translator): ?string;
}
