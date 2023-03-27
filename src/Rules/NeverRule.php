<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

class NeverRule implements RuleInterface
{

    public function execute(string $value): bool
    {
        return false;
    }
}