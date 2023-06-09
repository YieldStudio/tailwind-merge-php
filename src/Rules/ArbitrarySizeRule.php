<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Rules;

final class ArbitrarySizeRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'size';

    protected function testValue(string $value): bool
    {
        return false;
    }
}
