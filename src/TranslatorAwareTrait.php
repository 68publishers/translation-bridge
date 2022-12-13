<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use RuntimeException;
use Nette\Localization\Translator;
use function sprintf;
use function str_replace;

trait TranslatorAwareTrait
{
	protected Translator $translator;

	protected ?Translator $prefixedTranslator = null;

	private ?PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory = null;

	public function setTranslator(Translator $translator, ?PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory = null): void
	{
		$this->translator = $translator;

		if (null !== $prefixedTranslatorFactory) {
			$this->prefixedTranslatorFactory = $prefixedTranslatorFactory;
		}
	}

	public function getTranslator(): Translator
	{
		return $this->translator;
	}

	public function getPrefixedTranslator(): Translator
	{
		if (null !== $this->prefixedTranslator) {
			return $this->prefixedTranslator;
		}

		if (null === $this->prefixedTranslatorFactory) {
			throw new RuntimeException(sprintf(
				'Please set prefixed translator factory through method %s::setTranslator().',
				static::class
			));
		}

		return $this->prefixedTranslator = $this->prefixedTranslatorFactory->create($this->createTranslatorPrefix());
	}

	protected function createTranslatorPrefix(): string
	{
		return str_replace('\\', '_', static::class);
	}
}
