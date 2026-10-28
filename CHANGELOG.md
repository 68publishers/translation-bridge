# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2026-10-28
### Added
- Added support for `symfony/translation` v8 (requires `contributte/translation` >= 2.0.7 when used with the Contributte bridge).
- Added support for PHP 8.4 and 8.5.

### Changed
- Minimal PHP version is 8.3 (BC break).
- Minimal versions of `nette/di` (`^3.1.3`) and `nette/utils` (`^3.2.10 || ^4.0.1`), older releases cannot be installed on PHP 8.3.
- Updated PHPStan to v2 and `mockery/mockery` to `^1.6.12`.
- Updated GitHub actions (`actions/checkout@v4`, PHP 8.3 – 8.5 in the test matrix, coverage via pcov) and the docker dev environment (PHP 8.3 and 8.4).

### Fixed
- Translator-aware services that are not regular service definitions (e.g. `imported: true` services) are skipped instead of failing the container compilation on an assertion.

## [2.1.0] - 2024-08-06
### Added
- Added support for `nette/utils:^4.0`.
- Added support for `symfony/translation:^7.0`.

## [2.0.0] - 2022-12-13
### Added
- Added support for PHP `^8.1`.
- Added Dockerfile and docker-compose.yml with containers for all supported PHP versions, Makefile, GitHub actions, PHPStan and tests (full coverage).

### Changed
- Improved coding style.
- Moved the compiler extension `SixtyEightPublishers\TranslationBridge\Bridge\Contributte\DI\TranslationBridgeExtension` to `SixtyEightPublishers\TranslationBridge\Bridge\Nette\DI\ContributteTranslationBridgeExtension`.
- The compiler extension has been simplified.
- Usage changes, please read the updated [README](https://github.com/68publishers/translation-bridge/blob/v2.0.0/README.md).

### Removed
- Dropped support for PHP `7`.
- Dropped support for `kdyby/translation` (PHP 8 not supported).
- Removed interface `TranslatorLocaleResolverProviderInterface` and registration of resolvers with tags. Custom resolvers can be now registered as simple services.

## [1.2.4] - 2020-12-13
### Added
- Access to the fallback locales.
- Compatibility with `nette/utils` 3.2.

## [1.2.3] - 2020-11-23
### Added
- Prefixed translator aware.

## [1.2.2] - 2020-11-20
### Fixed
- Fixed Travis config and checks.

## [1.2.1] - 2020-11-19
### Fixed
- Changed implementation of the `LocaleResolver` class in the Contributte bridge (method `::addResolver()` can not be overloaded because its return type is declared as `self`, this caused a warning).

## [1.2] - 2020-11-19
### Added
- Translator locale resolvers.

## [1.1] - 2020-11-18
### Added
- Translator localizer.

## [1.0] - 2020-11-16
### Added
- Initial release.

[Unreleased]: https://github.com/68publishers/translation-bridge/compare/v3.0.0...HEAD
[3.0.0]: https://github.com/68publishers/translation-bridge/compare/v2.1.0...v3.0.0
[2.1.0]: https://github.com/68publishers/translation-bridge/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/68publishers/translation-bridge/compare/v1.2.4...v2.0.0
[1.2.4]: https://github.com/68publishers/translation-bridge/compare/v1.2.3...v1.2.4
[1.2.3]: https://github.com/68publishers/translation-bridge/compare/v1.2.2...v1.2.3
[1.2.2]: https://github.com/68publishers/translation-bridge/compare/v1.2.1...v1.2.2
[1.2.1]: https://github.com/68publishers/translation-bridge/compare/v1.2...v1.2.1
[1.2]: https://github.com/68publishers/translation-bridge/compare/v1.1...v1.2
[1.1]: https://github.com/68publishers/translation-bridge/compare/v1.0...v1.1
[1.0]: https://github.com/68publishers/translation-bridge/releases/tag/v1.0
