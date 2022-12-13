<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Bridge\Nette\DI;

interface TranslationProviderInterface
{
	/**
	 * @return array<string>
	 */
	public function getTranslationResources(): array;
}
