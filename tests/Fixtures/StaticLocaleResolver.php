<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

use Nette\Localization\Translator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class StaticLocaleResolver implements TranslatorLocaleResolverInterface
{
	public function __construct(
		private readonly string $locale,
	) {
	}

	public function resolveLocale(Translator $translator): ?string
	{
		return $this->locale;
	}
}
