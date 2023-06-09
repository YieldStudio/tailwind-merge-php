<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class NumberRule implements RuleInterface
{

    public function execute(string $value): bool
    {
        return is_numeric($value);
    }
}
