<?php

namespace YieldStudio\TailwindMerge\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * WIP
 */
class TailwindMergeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('tw', function ($expression) {
            $expression = is_null($expression) ? '()' : $expression;
            return "class=\"<?php echo tw_merge($expression) ?>\"";
        });
    }
}
