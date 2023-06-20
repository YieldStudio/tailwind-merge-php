<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string merge(...$classList)
 */
class TailwindMerge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tailwind-merge';
    }
}
