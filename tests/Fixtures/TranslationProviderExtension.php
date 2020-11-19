<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\TranslationBridge\DI\TranslationProviderInterface;

final class TranslationProviderExtension extends CompilerExtension implements TranslationProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getTranslationResources(): array
	{
		return [
			__DIR__ . '/../translations',
		];
	}
}
