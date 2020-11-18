<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Exception;

use Throwable;

final class InvalidLocaleException extends InvalidArgumentException
{
	/**
	 * @param string          $locale
	 * @param \Throwable|NULL $previous
	 *
	 * @return \SixtyEightPublishers\TranslationBridge\Exception\InvalidLocaleException
	 */
	public static function create(string $locale, ?Throwable $previous = NULL): self
	{
		return new static(sprintf(
			'Invalid locale %s.',
			$locale
		), 0, $previous);
	}
}
