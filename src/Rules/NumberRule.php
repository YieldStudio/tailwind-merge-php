<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

class NumberRule implements ValidatorInterface
{

    public function execute(string $value): bool
    {
        return is_numeric($value) || (new ArbitraryNumberRule)->execute($value);
    }
}