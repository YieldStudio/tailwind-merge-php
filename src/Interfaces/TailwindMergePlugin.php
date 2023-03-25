<?php

namespace YieldStudio\TailwindMerge\Interfaces;

use YieldStudio\TailwindMerge\TailwindMergeConfig;

interface TailwindMergePlugin
{

    public function __invoke(TailwindMergeConfig $config): TailwindMergeConfig;

}