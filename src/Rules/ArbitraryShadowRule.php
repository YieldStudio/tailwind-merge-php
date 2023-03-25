<?php

namespace YieldStudio\TailwindMerge\Rules;

class ArbitraryShadowRule extends ArbitraryValueRule
{
    protected const SHADOW_REGEX = '/^-?((\d+)?\.?(\d+)[a-z]+|0)_-?((\d+)?\.?(\d+)[a-z]+|0)/';

    protected function testValue(string $value): bool
    {
        return !!preg_match(self::SHADOW_REGEX, $value);
    }
}