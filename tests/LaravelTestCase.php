<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase;
use YieldStudio\TailwindMerge\Laravel\TailwindMergeServiceProvider;

abstract class LaravelTestCase extends TestCase
{
    use InteractsWithViews;
    use InteractsWithContainer;

    protected function getPackageProviders($app): array
    {
        return [
            TailwindMergeServiceProvider::class,
        ];
    }
}
