<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class IntegerRule implements RuleInterface
{
    public function execute(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
}
