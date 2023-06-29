# Rinvex Settings

**Rinvex Settings** is a Laravel package for setting management. You can add, update, and delete application settings with ease, and use like the detaul Laravel config options. It comes with the ability to override config options on runtime, and supports tenantable features out of the box.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-settings.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-settings)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-settings.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-settings/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-settings.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-settings)
[![StyleCI](https://styleci.io/repos/87599972/shield)](https://styleci.io/repos/87599972)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-settings.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-settings/blob/develop/LICENSE)


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/laravel-settings
    ```

2. Publish resources (migrations and config files):
    ```shell
    php artisan rinvex:publish:settings
    ```

3. Execute migrations via the following command:
    ```shell
    php artisan rinvex:migrate:settings
    ```

4. Done!


## Usage

It's worth mentioning that this package API is inspired by default Laravel config options, and it goes hand in hand with it. 

### Accessing Setting Values

You may easily access your setting values using the global `setting` function from anywhere in your application. The
setting values may be accessed using "dot" syntax, which includes the name of the setting group and option you wish
to access. A default value may also be specified and will be returned if the setting option does not exist:

```php
$value = setting('meta.description');

// Retrieve a default value if the configuration value does not exist...
$value = config('meta.description', 'Rinvex Settings is a Laravel package for setting management.');
```

To set configuration values at runtime, pass an array to the config function:

```php
config(['meta.description' => 'You can add, update, and delete application settings with ease, and use like the detaul Laravel config options.']);
```

### More Examples

```php
// Retrieve individual setting
$metaKeywords = setting('meta.keywords');
$metaDescription = setting('meta.description');

// Retrieve individual setting of an overridden config option
$appName = setting(‘app.name’);

// Retrieve group of settings
$meta = setting('meta’);

// Retrieve a default value if the setting value does not exist
$metaAuthor = setting('meta.author', 'Abdelrahman Omran');

// Set runtime setting values
setting(['meta.author' => 'Omranic']);
setting(['new.setting.key’ => Some Value Here!']);
```

> **Notes:** 
> - This package automatically spins through all settings and load them into an IoC collection.
> - It supports setting nesting, tenantable features if available, and config option override as well.
> - It executes early on application booting, which makes all options available for use across the whole app.
> - The underlying class for a setting is basically [Laravel Eloquent](https://laravel.com/docs/master/eloquent) model, which means you can use it the same intuitive way.

### Configuration Caching

To give your application a speed boost, you should cache all of your settings into a single file using the `setting:cache` Artisan command.
This will combine all of the configuration options for your application into a single file which can be quickly loaded by the framework.

You should typically run the `php artisan setting:cache` command as part of your production deployment process. The command should not
be run during local development as settings will frequently need to be changed during the course of your application's development.

> **Notes:**
> - Settings caching is disabled at the moment, until we figure out a way to handle tenantable settings cache, as it dynamically overrides app-wide global settings by nature.

### Advanced Usage

Settings can override default config options. Be aware this might change the default behavior of your application if not used wisely.

To override any config option using settings, simply create a new setting with the same key as that config option (example: `app.name`),
then set the `override_config` attribute to TRUE, set the override value you want to set, and save the setting. Next time your app
is booted and you call `config('app.name')`, it will automatically retrieve the value you set for `setting('app.name')`.

> **Notes:**
> - This `override_config` feature is not intended to override some of the core config options like db connection details for example, 
    as db connection config options are required and loaded at a very early stage. Be cautious when overriding config options.


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/rinvex-slack)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

We want to ensure that this package is secure for everyone. If you've discovered a security vulnerability in this package, we appreciate your help in disclosing it to us in a [responsible manner](https://en.wikipedia.org/wiki/Responsible_disclosure).

Publicly disclosing a vulnerability can put the entire community at risk. If you've discovered a security concern, please email us at [help@rinvex.com](mailto:help@rinvex.com). We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. We consider correspondence sent to [help@rinvex.com](mailto:help@rinvex.com) our highest priority, and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a security hotfix release will be deployed as soon as possible.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2022 Rinvex LLC, Some rights reserved.
