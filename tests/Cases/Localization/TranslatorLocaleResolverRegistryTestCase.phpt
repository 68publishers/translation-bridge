<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Cases\Localization;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverRegistry;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

require __DIR__ . '/../../bootstrap.php';

class TranslatorLocaleResolverRegistryTestCase extends TestCase
{
	/** @var \Nette\Localization\ITranslator */
	private $translator;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->translator = Mockery::mock(ITranslator::class);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		Mockery::close();
	}

	/**
	 * @return void
	 */
	public function testRegistryShouldReturnNullWhenIsEmptyOrLocaleIsNotResolved(): void
	{
		$emptyRegistry = new TranslatorLocaleResolverRegistry([]);

		Assert::null($emptyRegistry->resolveLocale($this->translator));

		$nonEmptyRegistry = new TranslatorLocaleResolverRegistry([
			$resolver = Mockery::mock(TranslatorLocaleResolverInterface::class),
		]);

		$resolver->shouldReceive('resolveLocale')->andReturnNull();

		Assert::null($nonEmptyRegistry->resolveLocale($this->translator));
	}

	/**
	 * @return void
	 */
	public function testRegistryShouldReturnLocaleWheyLocaleIsResolved(): void
	{
		$nullResolver = Mockery::mock(TranslatorLocaleResolverInterface::class);
		$enUsResolver = Mockery::mock(TranslatorLocaleResolverInterface::class);
		$csCzResolver= Mockery::mock(TranslatorLocaleResolverInterface::class);

		$nullResolver->shouldReceive('resolveLocale')->andReturnNull();
		$enUsResolver->shouldReceive('resolveLocale')->andReturn('en_US');
		$csCzResolver->shouldReceive('resolveLocale')->andReturn('cs_CZ');

		$firstRegistry = new TranslatorLocaleResolverRegistry([$enUsResolver, $csCzResolver]);

		$secondRegistry = new TranslatorLocaleResolverRegistry([$nullResolver, $csCzResolver]);

		Assert::same('en_US', $firstRegistry->resolveLocale($this->translator));
		Assert::same('cs_CZ', $secondRegistry->resolveLocale($this->translator));
	}
}

(new TranslatorLocaleResolverRegistryTestCase())->run();
