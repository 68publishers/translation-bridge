<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

use Nette\Localization\Translator;

interface TranslatorLocaleResolverInterface
{
	public function resolveLocale(Translator $translator): ?string;
}
