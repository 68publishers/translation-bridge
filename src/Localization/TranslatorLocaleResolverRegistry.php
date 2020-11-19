<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Localization;

use Nette\Localization\ITranslator;

final class TranslatorLocaleResolverRegistry implements TranslatorLocaleResolverInterface
{
	/** @var \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface[] */
	private $resolvers;

	/**
	 * @param \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface[] $resolvers
	 */
	public function __construct(array $resolvers)
	{
		$this->resolvers = (static function (TranslatorLocaleResolverInterface ...$resolvers): array {
			return $resolvers;
		})(...$resolvers);
	}

	/**
	 * {@inheritDoc}
	 */
	public function resolveLocale(ITranslator $translator): ?string
	{
		foreach ($this->resolvers as $resolver) {
			if (NULL !== ($locale = $resolver->resolveLocale($translator))) {
				return $locale;
			}
		}

		return NULL;
	}
}
