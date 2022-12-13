<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\Translator;

interface PrefixedTranslatorFactoryInterface
{
	public function create(string $prefix): Translator;
}
