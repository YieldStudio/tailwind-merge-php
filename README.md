# tailwind-merge-php

[![Latest Version](https://img.shields.io/github/release/yieldstudio/tailwind-merge-php?style=flat-square)](https://github.com/yieldstudio/tailwind-merge-php/releases)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/yieldstudio/tailwind-merge-php/tests.yml?branch=main)](https://github.com/yieldstudio/tailwind-merge-php/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/yieldstudio/tailwind-merge-php?style=flat-square)](https://packagist.org/packages/yieldstudio/tailwind-merge-php)

Utility function to efficiently merge [Tailwind CSS](https://tailwindcss.com) classes in PHP without style conflicts.

> Major version zero (0.y.z) is for initial development. Anything MAY change at any time. The public API SHOULD NOT be considered stable.

tailwind-merge-php is a PHP port of the excellent [tailwind-merge](https://github.com/dcastil/tailwind-merge) created by [dcastil](https://github.com/dcastil).


- Supports Tailwind v3.0 up to v3.3
- First-class support for laravel

```php
use YieldStudio\TailwindMerge\TailwindMerge;

TailwindMerge::instance()->merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]');
// → 'hover:bg-dark-red p-3 bg-[#B91C1C]'

tw_merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]');
// → 'hover:bg-dark-red p-3 bg-[#B91C1C]'
```

> ✍️ Complete documentation is being written

## Unit tests

To run the tests, just run `composer install` and `composer test`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://raw.githubusercontent.com/YieldStudio/.github/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [contact@yieldstudio.fr](mailto:contact@yieldstudio.fr) instead of using the issue tracker.

## Credits

- [James Hemery](https://github.com/jameshemery)
- Special thanks to [dcastil](https://github.com/dcastil) who built [tailwind-merge](https://github.com/dcastil/tailwind-merge)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
