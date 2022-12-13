<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

use InvalidArgumentException;
use Symfony\Component\Translation\Translator;
use SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException;

final class TranslatorLocalizer implements TranslatorLocalizerInterface
{
	private Translator $translator;

	public function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}

	public function setLocale(string $locale): void
	{
		try {
			$this->translator->setLocale($locale);
		} catch (InvalidArgumentException $e) {
			throw InvalidLocaleException::create($locale, $e);
		}
	}

	public function getLocale(): string
	{
		return $this->translator->getLocale();
	}

	public function getFallbackLocales(): array
	{
		return $this->translator->getFallbackLocales();
	}
}
