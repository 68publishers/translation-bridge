<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\ITranslator;

trait TranslatorAwareTrait
{
	/** @var \Nette\Localization\ITranslator|NULL */
	protected $translator;

	/**
	 * @param \Nette\Localization\ITranslator $translator
	 *
	 * @return void
	 */
	public function setTranslator(ITranslator $translator): void
	{
		$this->translator = $translator;
	}
}
