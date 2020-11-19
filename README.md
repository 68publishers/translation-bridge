# Translation bridge

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![Latest Version on Packagist][ico-version]][link-packagist]

A package contains bridges for the most used integrations of [symfomy/translation](https://symfony.com/doc/current/translation.html) into [Nette Framework](https://nette.org):

- [kdyby/translations](https://github.com/Kdyby/Translation)
- [contributte/translations](https://github.com/contributte/translation)

Why? Because we want to keep our bundles independent from specific integrations so applications can use any of the integrations mentioned above and will be still compatible with our bundles.

## Installation

The best way to install 68publishers/translation-bridge is using Composer:

```bash
$ composer require 68publishers/translation-bridge
```

## Configuration

```neon
extensions:
    # If you are using kdyby/translation:
    translation_bridge: SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\DI\TranslationBridgeExtension

    # Or if you are using contributte/translation:
    translation_bridge: SixtyEightPublishers\TranslationBridge\Bridge\Contributte\DI\TranslationBridgeExtension
```

## Usage

### Translation Resources Provider

Extensions can provide paths with translation resources.

```php
<?php

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\TranslationBridge\DI\TranslationProviderInterface;

final class FooBundleExtension extends CompilerExtension implements TranslationProviderInterface
{
    public function getTranslationResources(): array
    {
        return [
            __DIR__ . '/../translations',
        ];
    }
}
```

### Translator Aware

All services that implement an interface `TranslatorAwareInterface` will automatically receive the Translator instance.

```php
<?php

use SixtyEightPublishers\TranslationBridge\TranslatorAwareTrait;
use SixtyEightPublishers\TranslationBridge\TranslatorAwareInterface;

final class FooService implements TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    public function doSomething(): void
    {
        $this->translator->translate('....');
    }
}
```

### Prefixed Translator Factory

The Container contains an autowired service of type `PrefixedTranslatorFactoryInterface` for creating prefixed translators.

```php
<?php

use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;

final class FooService 
{
    private $translator;

    public function __construct(PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory) 
    {
        $this->translator = $prefixedTranslatorFactory->create('FooService');
    }
}
```

### Translator Localizer

The Container contains an service of type `TranslatorLocalizerInterface` for manipulating with the Translator locale.

```php
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;

final class FooService 
{
    private $localizer;

    public function __construct(TranslatorLocalizerInterface $localizer) 
    {
        $this->localizer = $localizer;
    }

    public function doSomething(): void
    {
        # Get the current locale
        $locale = $this->localizer->getLocale();

        # Set the new locale
        $this->localizer->setLocale('cs_CZ');
    }
}
```

### Translator Locale Resolver

The Translator's locale can be resolved with own resolvers like this:

```php
<?php

use Nette\Localization\ITranslator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class MyLocaleResolver implements TranslatorLocaleResolverInterface
{
    public function resolveLocale(ITranslator $translator) : ?string
    {
        # return a valid locale or NULL
    }
}
```

Resolvers can be registered manually with a tag `68publishers.translation_bridge.translator_locale_resolver` or through a `CompilerExtension` and each resolver can have priority.
They are sorted by priority in descending order so a Resolver with the highest priority will be called first. A default priority is 0.

Resolvers defined in `Kdyby` and `Contributte` integrations are automatically wrapped and provided into the main Resolver. Their priority is always 10.

```neon
    services: 
        -
            type: MyLocaleResolver
            tags:
                68publishers.translation_bridge.translator_locale_resolver: 15
```

Or

```php
<?php

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolver;
use SixtyEightPublishers\TranslationBridge\DI\TranslatorLocaleResolverProviderInterface;

final class FooBundleExtension extends CompilerExtension implements TranslatorLocaleResolverProviderInterface
{
    public function getTranslatorLocaleResolvers(): array
    {
        return [
            new TranslatorLocaleResolver(MyLocaleResolver::class, 15),
        ];
    }
}
```

## Contributing

Before committing any changes, don't forget to run

```bash
$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

and

```bash
$ composer run tests
```

[ico-version]: https://img.shields.io/packagist/v/68publishers/translation-bridge.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/68publishers/translation-bridge/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/68publishers/translation-bridge.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/68publishers/translation-bridge.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/68publishers/translation-bridge.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/68publishers/translation-bridge
[link-travis]: https://travis-ci.org/68publishers/translation-bridge
[link-scrutinizer]: https://scrutinizer-ci.com/g/68publishers/translation-bridge/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/68publishers/translation-bridge
[link-downloads]: https://packagist.org/packages/68publishers/translation-bridge
