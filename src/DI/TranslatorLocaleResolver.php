<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\DI;

final class TranslatorLocaleResolver
{
	/** @var mixed  */
	public $factory;

	/** @var int  */
	public $priority;

	/**
	 * @param mixed $factory
	 * @param int   $priority
	 */
	public function __construct($factory, int $priority = 0)
	{
		$this->factory = $factory;
		$this->priority = $priority;
	}
}
