<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\TranslatorAwareTrait;
use SixtyEightPublishers\TranslationBridge\TranslatorAwareInterface;

final class TranslatableService implements TranslatorAwareInterface
{
	use TranslatorAwareTrait;

	/**
	 * @return \Nette\Localization\ITranslator|NULL
	 */
	public function getTranslator(): ?ITranslator
	{
		return $this->translator;
	}
}
