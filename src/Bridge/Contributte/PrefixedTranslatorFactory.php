<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte;

use Nette\Localization\Translator;
use Contributte\Translation\Translator as ContributteTranslator;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;

final class PrefixedTranslatorFactory implements PrefixedTranslatorFactoryInterface
{
	public function __construct(
		private readonly ContributteTranslator $translator
	) {
	}

	public function create(string $prefix): Translator
	{
		return $this->translator->createPrefixedTranslator($prefix);
	}
}
