<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use YieldStudio\TailwindMerge\TailwindMerge;
use YieldStudio\TailwindMerge\TailwindMergeConfig;

final class TailwindMergeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(TailwindMerge::class, function (): TailwindMerge {
            return new TailwindMerge(
                TailwindMergeConfig::fromArray(
                    (array) config('tailwind-merge', []),
                    config('tailwind-merge.strategy', 'merge') === 'merge'
                )
            );
        });

        $this->app->alias(TailwindMerge::class, 'tailwind-merge');

        require_once __DIR__ . '/helpers.php';
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/tailwind-merge.php' => config_path('tailwind-merge.php'),
            ]);
        }

        ComponentAttributeBag::macro('tw', function (...$args) {
            /* @phpstan-ignore-next-line */
            $this->attributes['class'] = app(TailwindMerge::class)->merge(
                $args,
                /* @phpstan-ignore-next-line */
                $this->attributes['class'] ?? ''
            );

            return $this;
        });

        Blade::directive('tw', function ($expression) {
            $expression = is_null($expression) ? '()' : $expression;

            return "class=\"<?php echo tw($expression) ?>\"";
        });
    }

    public function provides(): array
    {
        return [
            TailwindMerge::class,
            'tailwind-merge',
        ];
    }
}
