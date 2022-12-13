<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\Translator;

interface TranslatorAwareInterface
{
	public function setTranslator(Translator $translator, ?PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory = null): void;
}
