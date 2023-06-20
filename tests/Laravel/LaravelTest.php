<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Blade;
use YieldStudio\TailwindMerge\TailwindMerge;
use YieldStudio\TailwindMerge\Tests\Laravel\TestSupport\Avatar;

use function Pest\version;

if (version_compare(version(), '2.0.0', '>=')) {
    test('Laravel is only used for Laravel integration')
        ->expect('Illuminate')
        ->toOnlyBeUsedIn('YieldStudio\\TailwindMerge\\Laravel');
}

it('tailwind merge is correctly bind on the container', function () {
    $instance = app('tailwind-merge');

    expect($instance)->toBeInstanceOf(TailwindMerge::class);

    expect(app('tailwind-merge'))->toBe($instance);
    expect(app(TailwindMerge::class))->toBe($instance);
});

it('customize configuration with merge strategy', function () {
    /* @phpstan-ignore-next-line */
    app()->bind('config', fn () => new Repository([
        'tailwind-merge' => [
            'class_groups' => [
                'fooKey' => [[
                    'fooKey' => ['bar', 'baz']]],
            ],
            'strategy' => 'merge',
        ],
    ]));

    /* @phpstan-ignore-next-line */
    expect(app('tailwind-merge')->merge('fooKey-bar fooKey-baz bg-red-100 bg-red-500'))->toBe('fooKey-baz bg-red-500');
});

it('customize configuration with replace strategy', function () {
    /* @phpstan-ignore-next-line */
    app()->bind('config', fn () => new Repository([
        'tailwind-merge' => [
            'class_groups' => [
                'fooKey' => [[
                    'fooKey' => ['bar', 'baz']]],
            ],
            'strategy' => 'replace',
        ],
    ]));

    /* @phpstan-ignore-next-line */
    expect(app('tailwind-merge')->merge('fooKey-bar fooKey-baz bg-red-100 bg-red-500'))->toBe('fooKey-baz bg-red-100 bg-red-500');
});

it('tw helper works correctly', function () {
    expect(tw('text-red-100 text-red-500'))->toBe('text-red-500');
});

it('blade components attribute works correctly', function () {
    Blade::component('avatar', Avatar::class);

    $this->blade('<x-avatar class="rounded-lg" />')->assertSee('<div class="rounded-lg"></div>', false);
});


it('blade directive works correctly', function () {
    $this->blade('<div @tw("text-red-100 text-red-500")></div>')->assertSee('class="text-red-500"', false);
});
