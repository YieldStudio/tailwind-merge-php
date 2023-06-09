<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

class IntegerRule implements RuleInterface
{
    public function execute(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}
