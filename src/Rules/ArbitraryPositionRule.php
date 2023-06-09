<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Rules;

final class ArbitraryPositionRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'position';

    protected function testValue(string $value): bool
    {
        return false;
    }
}
