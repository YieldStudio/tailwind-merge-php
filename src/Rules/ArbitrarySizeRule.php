<?php

namespace YieldStudio\TailwindMerge\Rules;

class ArbitrarySizeRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'size';

    protected function testValue(string $value): bool
    {
        return false;
    }
}