<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\DI;

interface TranslatorLocaleResolverProviderInterface
{
	/**
	 * @return \SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolver[]
	 */
	public function getTranslatorLocaleResolvers(): array;
}
