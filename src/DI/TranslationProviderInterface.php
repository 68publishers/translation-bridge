<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\DI;

interface TranslationProviderInterface
{
	/**
	 * @return string[]
	 */
	public function getTranslationResources(): array;
}
