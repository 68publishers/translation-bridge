<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\ITranslator;

interface TranslatorAwareInterface
{
	/**
	 * @param \Nette\Localization\ITranslator                                                 $translator
	 * @param \SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface|NULL $prefixedTranslatorFactory
	 *
	 * @return void
	 */
	public function setTranslator(ITranslator $translator, ?PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory = NULL): void;
}
