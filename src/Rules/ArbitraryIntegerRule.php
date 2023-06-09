<?php

namespace YieldStudio\TailwindMerge\Rules;

final class ArbitraryIntegerRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'number';

    protected function testValue(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}
