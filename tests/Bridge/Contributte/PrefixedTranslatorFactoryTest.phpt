<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Bridge\Contributte;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use Contributte\Translation\Translator;
use Contributte\Translation\PrefixedTranslator;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\PrefixedTranslatorFactory;

require __DIR__ . '/../../bootstrap.contributte.php';

final class PrefixedTranslatorFactoryTest extends TestCase
{
	public function testPrefixedTranslatorShouldBeCreated(): void
	{
		$translator = Mockery::mock(Translator::class);
		$prefixedTranslator = Mockery::mock(PrefixedTranslator::class);

		$translator->shouldReceive('createPrefixedTranslator')
			->once()
			->with('prefix')
			->andReturns($prefixedTranslator);

		$proxy = new PrefixedTranslatorFactory($translator);

		Assert::same($prefixedTranslator, $proxy->create('prefix'));
	}

	protected function tearDown(): void
	{
		Mockery::close();
	}
}

(new PrefixedTranslatorFactoryTest())->run();
