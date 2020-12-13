<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\SymfonyTranslation\Localization;

use InvalidArgumentException;
use Symfony\Component\Translation\Translator;
use SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;

final class TranslatorLocalizer implements TranslatorLocalizerInterface
{
	/** @var \Symfony\Component\Translation\Translator  */
	private $translator;

	/**
	 * @param \Symfony\Component\Translation\Translator $translator
	 */
	public function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLocale(string $locale): void
	{
		try {
			$this->translator->setLocale($locale);
		} catch (InvalidArgumentException $e) {
			throw InvalidLocaleException::create($locale, $e);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLocale(): string
	{
		return $this->translator->getLocale();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFallbackLocales(): array
	{
		return $this->translator->getFallbackLocales();
	}
}
