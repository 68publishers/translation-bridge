<h1 align="center">Translation bridge</h1>

<p align="center">
<a href="https://github.com/68publishers/translation-bridge/actions"><img alt="Checks" src="https://badgen.net/github/checks/68publishers/translation-bridge/master"></a>
<a href="https://coveralls.io/github/68publishers/translation-bridge?branch=master"><img alt="Coverage Status" src="https://coveralls.io/repos/github/68publishers/translation-bridge/badge.svg?branch=master"></a>
<a href="https://packagist.org/packages/68publishers/translation-bridge"><img alt="Total Downloads" src="https://badgen.net/packagist/dt/68publishers/translation-bridge"></a>
<a href="https://packagist.org/packages/68publishers/translation-bridge"><img alt="Latest Version" src="https://badgen.net/packagist/v/68publishers/translation-bridge"></a>
<a href="https://packagist.org/packages/68publishers/translation-bridge"><img alt="PHP Version" src="https://badgen.net/packagist/php/68publishers/translation-bridge"></a>
</p>

## About

The package contains bridges for the following integrations of [symfomy/translation](https://symfony.com/doc/current/translation.html) into [Nette Framework](https://nette.org):

- [contributte/translations](https://github.com/contributte/translation)

Why? Because we want to keep our bundles independent of specific integrations so applications can use any of the integrations mentioned above and will be still compatible with our bundles.

## Installation

The best way to install 68publishers/translation-bridge is using Composer:

```sh
$ composer require 68publishers/translation-bridge
```

## Configuration

```neon
extensions:
    # if you are using contributte/translation:
    translation_bridge: SixtyEightPublishers\TranslationBridge\Bridge\Nette\DI\ContributteTranslationBridgeExtension
```

## Usage

### Translation Resources Provider

Extensions can provide paths with translation resources.

```php
use Nette\DI\CompilerExtension;
use SixtyEightPublishers\TranslationBridge\Bridge\Nette\DI\TranslationProviderInterface;

final class MyBundleExtension extends CompilerExtension implements TranslationProviderInterface
{
    public function getTranslationResources(): array
    {
        return [
            __DIR__ . '/translations',
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

final class MyService implements TranslatorAwareInterface
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

use Nette\Localization\Translator;
use SixtyEightPublishers\TranslationBridge\PrefixedTranslatorFactoryInterface;

final class MyService
{
    private Translator $translator;

    public function __construct(PrefixedTranslatorFactoryInterface $prefixedTranslatorFactory)
    {
        $this->translator = $prefixedTranslatorFactory->create('MyService');
    }
}
```

### Translator Localizer

The Container contains the service of type `TranslatorLocalizerInterface` for manipulating with the Translator locale.

```php
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;

final class MyService
{
    public function __construct(
        private readonly TranslatorLocalizerInterface $localizer
    ) {}

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

use Nette\Localization\Translator;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocaleResolverInterface;

final class MyLocaleResolver implements TranslatorLocaleResolverInterface
{
    public function resolveLocale(Translator $translator) : ?string
    {
        # return a valid locale or NULL
    }
}
```

```neon
services:
	- MyLocaleResolver
```

## Contributing

Before opening a pull request, please check your changes using the following commands

```bash
$ make init # to pull and start all docker images

$ make cs.check
$ make stan
$ make tests.all
```
