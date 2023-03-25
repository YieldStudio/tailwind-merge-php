<?php

namespace YieldStudio\TailwindMerge\Rules;

class ArbitraryPositionRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'position';

    protected function testValue(string $value): bool
    {
        return false;
    }
}