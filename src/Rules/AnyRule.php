<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

class AnyRule implements RuleInterface
{

    public function execute(string $value): bool
    {
        return true;
    }
}