<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

interface TranslatorLocalizerInterface
{
	/**
	 * @throws \SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException
	 */
	public function setLocale(string $locale): void;

	public function getLocale(): string;

	/**
	 * @return array<string>
	 */
	public function getFallbackLocales(): array;
}
