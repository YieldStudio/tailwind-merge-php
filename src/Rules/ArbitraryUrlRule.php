<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Rules;

final class ArbitraryUrlRule extends ArbitraryValueRule
{
    protected ?string $parameter = 'url';

    protected function testValue(string $value): bool
    {
        return str_starts_with($value, 'url(');
    }
}
