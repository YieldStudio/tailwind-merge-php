<?php

namespace YieldStudio\TailwindMerge\Rules;

class ArbitraryUrlRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'url';

    protected function testValue(string $value): bool
    {
        return str_starts_with($value, 'url(');
    }
}