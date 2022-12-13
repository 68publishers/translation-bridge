<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

use SixtyEightPublishers\TranslationBridge\TranslatorAwareTrait;
use SixtyEightPublishers\TranslationBridge\TranslatorAwareInterface;

final class TranslatableServiceWithCustomPrefix implements TranslatorAwareInterface
{
	use TranslatorAwareTrait;

	public function __construct(
		private readonly string $prefix
	) {
	}

	protected function createTranslatorPrefix(): string
	{
		return $this->prefix;
	}
}
