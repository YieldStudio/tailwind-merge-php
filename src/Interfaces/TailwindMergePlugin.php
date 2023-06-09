<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Interfaces;

use YieldStudio\TailwindMerge\TailwindMergeConfig;

interface TailwindMergePlugin
{
    public function __invoke(TailwindMergeConfig $config): TailwindMergeConfig;
}
