<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\ITranslator;

interface TranslatorAwareInterface
{
	/**
	 * @param \Nette\Localization\ITranslator $translator
	 *
	 * @return void
	 */
	public function setTranslator(ITranslator $translator): void;
}
