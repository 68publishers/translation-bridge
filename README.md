# Translation bridge

A package contains bridges for the most used integrations of [synfomy/translation](https://symfony.com/doc/current/translation.html) into [Nette Framework](https://nette.org):

- [kdyby/translations](https://github.com/Kdyby/Translation)
- [contributte/translations](https://github.com/contributte/translation)

Why? because we want to keep our bundles independent from specific integrations so applications can use any of the integrations mentioned above and will be still compatible with our bundles.

## Installation

The best way to install 68publishers/translation-bridge is using Composer:

```bash
$ composer require 68publishers/translation-bridge
```

## Configuration

```neon
extensions:
    # if you are using kdyby/translation:
    translation_bridge: SixtyEightPublishers\TranslationBridge\Bridge\Kdyby\DI\TranslationBridgeExtension

    # of if you are using contributte/translation:
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

## Contributing

Before committing any changes, don't forget to run

```bash
$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

and

```bash
$ composer run tests
```
