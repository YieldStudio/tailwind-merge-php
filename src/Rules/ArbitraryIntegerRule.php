<?php

namespace YieldStudio\TailwindMerge\Rules;

class ArbitraryIntegerRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'number';

    protected function testValue(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}