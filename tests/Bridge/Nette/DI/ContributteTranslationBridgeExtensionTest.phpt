<?php

declare(strict_types=1);

namespace SixtyEightPublishers\TranslationBridge\Tests\Bridge\Nette\DI;

use Closure;
use Tester\Assert;
use Tester\TestCase;
use RuntimeException;
use Tester\CodeCoverage\Collector;
use Contributte\Translation\Translator as ContributteTranslator;
use Symfony\Component\Translation\Translator as SymfonyTranslator;
use Contributte\Translation\LocaleResolver as ContributteLocaleResolver;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizer;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\LocaleResolver;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\StaticLocaleResolver;
use Contributte\Translation\PrefixedTranslator as ContributtePrefixedTranslator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use SixtyEightPublishers\TranslationBridge\Bridge\Contributte\PrefixedTranslatorFactory;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverRegistry;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableServiceFactoryInterface;
use SixtyEightPublishers\TranslationBridge\Tests\Fixtures\TranslatableServiceWithCustomPrefix;
use function assert;
use function realpath;
use function call_user_func;

require __DIR__ . '/../../../bootstrap.contributte.php';

final class ContributteTranslationBridgeExtensionTest extends TestCase
{
	public function testExceptionShouldBeThrownIfTranslationExtensionIsMissing(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/config.contributte.error.missingTranslationExtension.neon'),
			RuntimeException::class,
			'Please register the compiler extension of type Contributte\Translation\DI\TranslationExtension.'
		);
	}

	public function testIntegrationWithMinimalConfiguration(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/config.contributte.neon');

		Assert::type(TranslatorLocalizer::class, $container->getByType(TranslatorLocalizerInterface::class, false));
		Assert::type(PrefixedTranslatorFactory::class, $container->getByType(PrefixedTranslatorFactoryInterface::class, false));

		$contributteResolver = $container->getByType(ContributteLocaleResolver::class);
		assert($contributteResolver instanceof ContributteLocaleResolver);

		Assert::same([], $contributteResolver->getResolvers());
		Assert::null($container->getByType(LocaleResolver::class, false));
	}

	public function testCustomTranslatorLocaleResolverShouldBeRegistered(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/config.contributte.withLocaleResolver.neon');
		$contributteResolver = $container->getByType(ContributteLocaleResolver::class);
		assert($contributteResolver instanceof ContributteLocaleResolver);

		Assert::same([LocaleResolver::class], $contributteResolver->getResolvers());

		$resolver = $container->getByType(LocaleResolver::class);
		assert($resolver instanceof LocaleResolver);

		call_user_func(Closure::bind(
			static function () use ($resolver) {
				$registry = $resolver->translatorLocaleResolver;

				call_user_func(Closure::bind(
					static function () use ($registry) {
						Assert::equal([new StaticLocaleResolver('de')], $registry->resolvers);
					},
					null,
					TranslatorLocaleResolverRegistry::class
				));
			},
			null,
			LocaleResolver::class
		));
	}

	public function testTranslatorShouldBeAware(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/config.contributte.withTranslatorAwareServices.neon');
		$service1 = $container->getByType(TranslatableServiceWithCustomPrefix::class);
		$service2Factory = $container->getByType(TranslatableServiceFactoryInterface::class);
		assert($service1 instanceof TranslatableServiceWithCustomPrefix && $service2Factory instanceof TranslatableServiceFactoryInterface);

		$service2 = $service2Factory->create();

		Assert::type(ContributteTranslator::class, $service1->getTranslator());
		Assert::type(ContributtePrefixedTranslator::class, $service1->getPrefixedTranslator());

		Assert::type(ContributteTranslator::class, $service2->getTranslator());
		Assert::type(ContributtePrefixedTranslator::class, $service2->getPrefixedTranslator());
	}

	public function testTranslationsShouldBeProvidedByAnotherCompilerExtension(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/config.contributte.withTranslationProviderExtension.neon');
		$translator = $container->getByType(ContributteTranslator::class);
		assert($translator instanceof ContributteTranslator);

		call_user_func(Closure::bind(
			static function () use ($translator) {
				Assert::same([
					'en' => [
						[
							'neon',
							realpath(__DIR__ . '/../../../Fixtures/translations/dictionary.en.neon'),
							'dictionary',
						],
					],
				], $translator->resources);
			},
			null,
			SymfonyTranslator::class
		));
	}

	protected function tearDown(): void
	{
		# save manually partial code coverage to free memory
		if (Collector::isStarted()) {
			Collector::save();
		}
	}
}

(new ContributteTranslationBridgeExtensionTest())->run();
