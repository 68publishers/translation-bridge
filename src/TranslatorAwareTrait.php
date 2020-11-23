<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge;

use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\Exception\RuntimeException;

trait TranslatorAwareTrait
{
	/** @var \Nette\Localization\ITranslator|NULL */
	protected $translator;

	/** @var \Nette\Localization\ITranslator|NULL */
	protected $prefixedTranslator;

	/** @var \SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface|NULL */
	private $prefixedTranslatorFactory;

	/**
	 * @param \Nette\Localization\ITranslator                                                 $translator
	 * @param \SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface|null $prefixedTranslatorFactory
	 *
	 * @return void
	 */
	public function setTranslator(ITranslator $translator, ?PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory = NULL): void
	{
		$this->translator = $translator;

		if (NULL !== $prefixedTranslatorFactory) {
			$this->prefixedTranslatorFactory = $prefixedTranslatorFactory;
		}
	}

	/**
	 * @return \Nette\Localization\ITranslator
	 */
	public function getTranslator(): ITranslator
	{
		return $this->translator;
	}

	/**
	 * @return \Nette\Localization\ITranslator
	 */
	public function getPrefixedTranslator(): ITranslator
	{
		if (NULL !== $this->prefixedTranslator) {
			return $this->prefixedTranslator;
		}

		if (NULL === $this->prefixedTranslatorFactory) {
			throw new RuntimeException(sprintf(
				'Please set prefixed translator factory through method %s::setTranslator().',
				static::class
			));
		}

		return $this->prefixedTranslator = $this->prefixedTranslatorFactory->create($this->createTranslatorPrefix());
	}

	/**
	 * @return string
	 */
	protected function createTranslatorPrefix(): string
	{
		return str_replace('\\', '_', static::class);
	}
}
