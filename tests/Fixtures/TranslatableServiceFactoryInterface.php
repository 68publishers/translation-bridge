<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

interface TranslatableServiceFactoryInterface
{
	public function create(): TranslatableService;
}
