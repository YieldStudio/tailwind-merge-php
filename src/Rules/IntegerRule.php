<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

class IntegerRule implements ValidatorInterface
{
    public function execute(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) || (new ArbitraryIntegerRule())->execute($value);
    }
}