<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Fixtures;

interface TranslatableServiceFactoryInterface
{
	/**
	 * @return \SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableService
	 */
	public function create(): TranslatableService;
}
