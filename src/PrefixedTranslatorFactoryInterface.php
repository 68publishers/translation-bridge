<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\ITranslator;

interface PrefixedTranslatorFactoryInterface
{
	/**
	 * @param string $prefix
	 *
	 * @return \Nette\Localization\ITranslator
	 */
	public function create(string $prefix): ITranslator;
}
