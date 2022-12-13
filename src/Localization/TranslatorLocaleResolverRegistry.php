<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

use Nette\Localization\Translator;

final class TranslatorLocaleResolverRegistry implements TranslatorLocaleResolverInterface
{
	/** @var array<TranslatorLocaleResolverInterface> */
	private array $resolvers;

	/**
	 * @param array<TranslatorLocaleResolverInterface> $resolvers
	 */
	public function __construct(array $resolvers = [])
	{
		$this->resolvers = (static fn (TranslatorLocaleResolverInterface ...$resolvers): array => $resolvers)(...$resolvers);
	}

	public function resolveLocale(Translator $translator): ?string
	{
		foreach ($this->resolvers as $resolver) {
			$locale = $resolver->resolveLocale($translator);

			if (null !== $locale) {
				return $locale;
			}
		}

		return null;
	}
}
