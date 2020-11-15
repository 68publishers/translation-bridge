<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Kdyby;

use Kdyby\Translation\Translator;
use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;

final class PrefixedTranslatorFactory implements PrefixedTranslatorFactoryInterface
{
	/** @var \Kdyby\Translation\Translator  */
	private $translator;

	/**
	 * @param \Kdyby\Translation\Translator $translator
	 */
	public function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(string $prefix): ITranslator
	{
		return $this->translator->domain($prefix);
	}
}
