<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Contributte;

use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;

final class PrefixedTranslatorFactory implements PrefixedTranslatorFactoryInterface
{
	/** @var \Contributte\Translation\Translator|\Nette\Localization\ITranslator  */
	private $translator;

	/**
	 * @param \Nette\Localization\ITranslator|\Contributte\Translation\Translator $translator
	 */
	public function __construct(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(string $prefix): ITranslator
	{
		return $this->translator->createPrefixedTranslator($prefix);
	}
}
