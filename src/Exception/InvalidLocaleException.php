<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Exception;

use Throwable;
use InvalidArgumentException;
use function sprintf;

final class InvalidLocaleException extends InvalidArgumentException
{
	public static function create(string $locale, ?Throwable $previous = null): self
	{
		return new self(sprintf(
			'Invalid locale "%s".',
			$locale
		), 0, $previous);
	}
}
