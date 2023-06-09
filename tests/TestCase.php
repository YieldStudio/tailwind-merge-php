<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use YieldStudio\TailwindMerge\TailwindMerge;
use YieldStudio\TailwindMerge\TailwindMergeConfig;

abstract class TestCase extends BaseTestCase
{
    public function twMerge(array $classLists, ?TailwindMergeConfig $config = null, array $plugins = []): void
    {
        $twMerge = new TailwindMerge($config);

        if (! empty($plugins)) {
            $twMerge->extend(...$plugins);
        }

        foreach ($classLists as $classList => $expected) {
            expect($twMerge->merge($classList))->toBe($expected);
        }
    }
}
