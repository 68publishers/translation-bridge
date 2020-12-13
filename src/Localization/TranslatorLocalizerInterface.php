<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

interface TranslatorLocalizerInterface
{
	/**
	 * @param string $locale
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException
	 */
	public function setLocale(string $locale): void;

	/**
	 * @return string
	 */
	public function getLocale(): string;

	/**
	 * @return string[]
	 */
	public function getFallbackLocales(): array;
}
